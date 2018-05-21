<?php
class PaymentController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "trx/payment.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
		//load data to datatables
        $this->Set("userLevel",$this->userLevel);
        $this->Set("outletId",$this->userOutletId);
	}

	public function add1(){
        $result['crud'] = 'N';
        $result['error'] = 'No Data Posted!';
        $result['result'] = 0;
        print json_encode($result);
    }

	public function add() {
	    require_once (MODEL . "inventory/stokin.php");
        require_once (MODEL . "trx/kas.php");
        $payment = new Payment();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $payment->OutletId = $this->userOutletId;
            $payment->TrxDate = strtotime($this->GetPostValue("TrxDate"));
            $payment->SuppCode = $this->GetPostValue("SuppCode");
            $payment->ReffNo = $this->GetPostValue("ReffNo");
            $payment->Jumlah = $this->GetPostValue("Jumlah");
            $payment->TrxNo = $payment->AutoTrxNo($payment->OutletId,$payment->TrxDate);
            $payment->TrxMode = 1;
            $payment->TrxStatus = 2;
            $payment->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($payment->Insert() == 1) {
                //update penerimaan barang/tagihan
                $stokin = new StokIn();
                $stokin = $stokin->FindByStokInNo($payment->ReffNo);
                $id = 0;
                $pam = 0;
                if ($stokin != null){
                    $id = $stokin->Id;
                    $pam = $stokin->PayAmt + $payment->Jumlah;
                    $stokin->PayAmt = $pam;
                    $stokin->StokInStatus = 2;
                    $stokin->Update($id);
                }

                //catat pada transaksi kas
                $kas = new Kas();
                $kas->OutletId = $this->userOutletId;
                $kas->TrxDate = $payment->TrxDate;
                $kas->TrxType = 3;
                $kas->TrxMode = 2;
                $kas->TrxStatus = 2;
                $kas->TrxNo = $kas->AutoTrxNo($this->userOutletId,$kas->TrxDate);
                $kas->Jumlah = $payment->Jumlah;
                $kas->Notes = 'Pembayaran Tagihan No: '.$payment->ReffNo;
                $kas->ReffNo = $payment->TrxNo;
                $kas->CreatebyId = $payment->CreatebyId;
                $kas->Insert();

                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Payment -> '.$payment->Payment,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
               // $log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Payment ->  '.$payment->Payment,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Trx No: '%s' telah ada pada database !", $payment->TrxNo);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
            }
        }else{
            $result['error'] = 'No Data Posted!';
            $result['result'] = 0;
        }
        print json_encode($result);
    }

	public function getdata(){
	    $id = $_POST["id"];
	    $payment = new Payment();
	    $payment = $payment->LoadById($id);
	    $data = array();
	    if ($payment != null){
            $data['id'] = $payment->Id;
            $data['kategori'] = $payment->Payment;
            $data['entity_id'] = $payment->EntityId;
        }
        print json_encode($data);
    }

	public function delete() {
	    require_once (MODEL ."inventory/stokin.php");
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$payment = new Payment();
		$payment = $payment->FindById($id);
		$rfn = null;
		$jml = 0;
		if ($payment == null) {
            $result['error'] = 'Data Payment yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    $rfn = $payment->ReffNo;
		    $jml = $payment->Jumlah;
            if ($payment->Delete($payment->Id) == 1) {
                //update data tagihan
                $stokin = new StokIn();
                $stokin = $stokin->FindByStokInNo($rfn);
                $id = 0;
                $pam = 0;
                if ($stokin != null){
                    $id = $stokin->Id;
                    $pam = $stokin->PayAmt - $jml;
                    $stokin->PayAmt = $pam;
                    $stokin->StokInStatus = 1;
                    $stokin->Update($id);
                }

                //hapus transaksi kas
                $kas = new Kas();
                $kas = $kas->FindByReffNo($payment->TrxNo);
                $kid = null;
                if ($kas != null){
                    $kid = $kas->Id;
                    $kas->Delete($kid);
                }

                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Payment -> '.$payment->Payment,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Payment -> '.$payment->Payment,'-','Failed');
                $result['error'] = printf("Gagal menghapus data pembayaran: '%s'. Message: %s", $payment->TrxNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonPayment(){
	    $payment = new Payment();
	    $paymentLists = $payment->GetJsonPayment($this->userOutletId);
	    print json_encode($paymentLists);
    }

    public function report(){
        require_once (MODEL . "master/outlet.php");
        $month = (int)date("n");
        $year = (int)date("Y");
        if (count($this->postData) > 0) {
            $outletId =  $this->GetPostValue("outletId");
            $startDate = strtotime($this->GetPostValue("startDate"));
            $endDate = strtotime($this->GetPostValue("endDate"));
            $jnsLaporan = $this->GetPostValue("jnsLaporan");
            $outPut = $this->GetPostValue("outPut");
            $payment = new Payment();
            $reports = $payment->LoadByOutletId($outletId,$startDate,$endDate);
        }else{
            $outletId = $this->userOutletId;
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $payment = new Payment();
            $reports = $payment->LoadByOutletId($outletId,$startDate,$endDate);
            $jnsLaporan = 1;
            $outPut = 1;
        }
        //var_dump($reports);
        //exit;
        $loader = new Outlet();
        //if ($this->userOutletId == 0) {
        //    $outlets = $loader->LoadAll($this->userCompanyId);
        //}else{
            $outlets = $loader->LoadById($this->userOutletId);
        //}
        $this->Set("outlets",$outlets);
        $this->Set("outletId",$outletId);
        $this->Set("startDate",$startDate);
        $this->Set("endDate",$endDate);
        $this->Set("jnsLaporan",$jnsLaporan);
        $this->Set("outPut",$outPut);
        $this->Set("reports",$reports);
    }

    public function getSupplierByOutletId($supCd = null){
        require_once (MODEL . "inventory/supplier.php");
        $rst = new Supplier();
        $rst = $rst->LoadAll(0,0);
        if($rst == null){
            print('<select class="form-control" name="cboSuppCode" id="cboSuppCode" required>');
            print("<option value='0'>Pilih Supplier</option>");
            print("</select>");
        }else{
            /** @var $rst Supplier[]*/
            print('<select class="form-control" name="cboSuppCode" id="cboSuppCode" required>');
            print("<option value='0'>Pilih Supplier</option>");
            foreach ($rst as $supplier){
                if ($supCd == $supplier->Kode) {
                    printf('<option value="%s" selected="selected"> %s - %s</option>', $supplier->Kode, $supplier->Kode, $supplier->Nama);
                }else{
                    printf('<option value="%s"> %s - %s</option>', $supplier->Kode, $supplier->Kode, $supplier->Nama);
                }
            }
            print("</select>");
        }
    }

    public function getOutstandingBySuppCode($supCd = null){
        require_once (MODEL . "inventory/stokin.php");
        $rst = new StokIn();
        $rst = $rst->LoadOutstandingStokIn($this->userOutletId,$supCd);
        if($rst == null){
            print('<select class="form-control" name="cboReffNo" id="cboReffNo" required>');
            print("<option value='0'>Pilih Tagihan</option>");
            print("</select>");
        }else{
            /** @var $rst StokIn[]*/
            print('<select class="form-control" name="cboReffNo" id="cboReffNo" required>');
            print("<option value='0'>Pilih Tagihan</option>");
            foreach ($rst as $stokin){
                printf('<option value="%s|%s"> %s - %s - Rp. %s</option>', $stokin->StokInNo,$stokin->SubTotal - $stokin->PayAmt, $stokin->StokInNo, $stokin->StokInDate, number_format($stokin->SubTotal - $stokin->PayAmt,0));
            }
            print("</select>");
        }
    }
}
