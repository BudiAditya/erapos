<?php
class ReceiveController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "trx/receive.php");
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
	    require_once (MODEL . "trx/salepusat.php");
        require_once (MODEL . "trx/kas.php");
        $receive = new Receive();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $receive->OutletId = $this->userOutletId;
            $receive->TrxDate = strtotime($this->GetPostValue("TrxDate"));
            $receive->CustCode = $this->GetPostValue("CustCode");
            $receive->ReffNo = $this->GetPostValue("ReffNo");
            $receive->Jumlah = $this->GetPostValue("Jumlah");
            $receive->TrxNo = $receive->AutoTrxNo($receive->OutletId,$receive->TrxDate);
            $receive->TrxMode = 1;
            $receive->TrxStatus = 2;
            $receive->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($receive->Insert() == 1) {
                //update penerimaan barang/tagihan
                $salepusat = new SalePusat();
                $salepusat = $salepusat->FindByTrxNo($receive->ReffNo);
                $id = 0;
                $pam = 0;
                if ($salepusat != null){
                    $id = $salepusat->Id;
                    $pam = $salepusat->PayAmt + $receive->Jumlah;
                    $salepusat->PayAmt = $pam;
                    $salepusat->TrxStatus = 2;
                    if ($salepusat->Update($id) == 1){
                        //catat pada transaksi kas
                        $kas = new Kas();
                        $kas->OutletId = $this->userOutletId;
                        $kas->TrxDate = $receive->TrxDate;
                        $kas->TrxType = 2;
                        $kas->TrxMode = 2;
                        $kas->TrxStatus = 2;
                        $kas->TrxNo = $kas->AutoTrxNo($this->userOutletId,$kas->TrxDate);
                        $kas->Jumlah = $receive->Jumlah;
                        $kas->Notes = 'Penerimaan Piutang No: '.$receive->ReffNo;
                        $kas->ReffNo = $receive->TrxNo;
                        $kas->CreatebyId = $receive->CreatebyId;
                        $kas->Insert();
                    }
                }

                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Receive -> '.$receive->Receive,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
               // $log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Receive ->  '.$receive->Receive,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Trx No: '%s' telah ada pada database !", $receive->TrxNo);
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
	    $receive = new Receive();
	    $receive = $receive->LoadById($id);
	    $data = array();
	    if ($receive != null){
            $data['id'] = $receive->Id;
            $data['kategori'] = $receive->Receive;
            $data['entity_id'] = $receive->EntityId;
        }
        print json_encode($data);
    }

	public function delete() {
	    require_once (MODEL ."trx/salepusat.php");
        require_once (MODEL ."trx/kas.php");
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$receive = new Receive();
		$receive = $receive->FindById($id);
		$rfn = null;
		$jml = 0;
		if ($receive == null) {
            $result['error'] = 'Data Receive yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    $rfn = $receive->ReffNo;
		    $jml = $receive->Jumlah;
            if ($receive->Delete($receive->Id) == 1) {
                //update data tagihan
                $salepusat = new SalePusat();
                $salepusat = $salepusat->FindByTrxNo($rfn);
                $id = 0;
                $pam = 0;
                if ($salepusat != null){
                    $id = $salepusat->Id;
                    $pam = $salepusat->PayAmt - $jml;
                    $salepusat->PayAmt = $pam;
                    $salepusat->TrxStatus = 1;
                    if ($salepusat->Update($id) == 1){
                        //hapus transaksi kas
                        $kas = new Kas();
                        $kas = $kas->FindByReffNo($receive->TrxNo);
                        $kid = null;
                        if ($kas != null){
                            $kid = $kas->Id;
                            $kas->Delete($kid);
                        }
                    }
                }

                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Receive -> '.$receive->Receive,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Receive -> '.$receive->Receive,'-','Failed');
                $result['error'] = printf("Gagal menghapus data pembayaran: '%s'. Message: %s", $receive->TrxNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonReceive(){
	    $receive = new Receive();
	    $receiveLists = $receive->GetJsonReceive($this->userOutletId);
	    print json_encode($receiveLists);
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
            $receive = new Receive();
            $reports = $receive->Load4Report($outletId,$startDate,$endDate);
        }else{
            $outletId = '';
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $receive = new Receive();
            $reports = $receive->Load4Report($outletId,$startDate,$endDate);
            $jnsLaporan = 1;
            $outPut = 1;
        }
        //var_dump($reports);
        //exit;
        $loader = new Outlet();
        if ($this->userOutletId == 1) {
            $outlets = $loader->LoadAll($this->userCompanyId);
        }else{
            $outlets = $loader->LoadById($this->userOutletId);
        }
        $this->Set("outlets",$outlets);
        $this->Set("outletId",$outletId);
        $this->Set("startDate",$startDate);
        $this->Set("endDate",$endDate);
        $this->Set("jnsLaporan",$jnsLaporan);
        $this->Set("outPut",$outPut);
        $this->Set("reports",$reports);
    }

    public function getCustomerByOutletId($custCd = null){
        //require_once (MODEL . "master/customer.php");
        require_once (MODEL . "master/outlet.php");
        $rst = new Outlet();
        $rst = $rst->LoadAll(1);
        if($rst == null){
            print('<select class="form-control" name="cboCustCode" id="cboCustCode" required>');
            print("<option value='0'>Pilih Customer</option>");
            print("</select>");
        }else{
            /** @var $rst Outlet[]*/
            print('<select class="form-control" name="cboCustCode" id="cboCustCode" required>');
            print("<option value='0'>Pilih Customer</option>");
            foreach ($rst as $outlet){
                if ($outlet->Id <> $this->userOutletId) {
                    if ($custCd == $outlet->Kode) {
                        printf('<option value="%s" selected="selected"> %s - %s</option>', $outlet->Kode, $outlet->Kode, $outlet->OutletName);
                    } else {
                        printf('<option value="%s"> %s - %s</option>', $outlet->Kode, $outlet->Kode, $outlet->OutletName);
                    }
                }
            }
            print("</select>");
        }
    }

    public function getOutstandingByCustCode($custCd = null){
        require_once (MODEL . "trx/salepusat.php");
        $rst = new SalePusat();
        $rst = $rst->LoadOutstandingSalePusat($this->userOutletId,$custCd);
        if($rst == null){
            print('<select class="form-control" name="cboReffNo" id="cboReffNo" required>');
            print("<option value='0'>Pilih Invoice</option>");
            print("</select>");
        }else{
            /** @var $rst SalePusat[]*/
            print('<select class="form-control" name="cboReffNo" id="cboReffNo" required>');
            print("<option value='0'>Pilih Invoice</option>");
            foreach ($rst as $salePusat){
                printf('<option value="%s|%s"> %s - %s - Rp. %s</option>', $salePusat->TrxNo,$salePusat->SubTotal - $salePusat->PayAmt, $salePusat->TrxNo, $salePusat->TrxDate, number_format($salePusat->SubTotal - $salePusat->PayAmt,0));
            }
            print("</select>");
        }
    }
}
