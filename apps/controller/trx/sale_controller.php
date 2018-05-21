<?php
class SaleController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "trx/sale.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
	    //require_once(MODEL . "master/outlet.php");
		//load data to datatables
        //$outlets = new Outlet();
        //$outlets = $outlets->LoadAll($this->userCompanyId);
        //$this->Set("outlets",$outlets);
        $sales = new Sale();
        //if ($this->userLevel > 3) {
        //    $sales = $sales->LoadByOutletId(0,false);
        //}else{
        $sales = $sales->LoadByOutletId($this->userOutletId,false);
        //}
        $this->Set("sales",$sales);
        $this->Set("outletId",$this->userOutletId);
        $this->Set("userLvl",$this->userLevel);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "master/customer.php");
        $sale = new Sale();
        //$log = new UserAdmin();
        $sale->TrxNo = 0;
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $customers = new Customer();
        $customers = $customers->LoadAll(0,$this->userOutletId);
        $this->Set("customers", $customers);
        $produks = new Produk();
        $produks = $produks->LoadProdukDijual($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("sale", $sale);
        $this->Set("userLvl",$this->userLevel);
    }

    public function edit($trxNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "master/customer.php");
        $sale = new Sale();
        //$log = new UserAdmin();
        $sale->FindByTrxNo($trxNo);
        if ($sale == null || $sale->TrxStatus > 0){
            redirect_url("trx.sale");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $customers = new Customer();
        $customers = $customers->LoadAll(0,$this->userOutletId);
        $this->Set("customers", $customers);
        $produks = new Produk();
        $produks = $produks->LoadProdukDijual($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("sale", $sale);
        $this->Set("userLvl",$this->userLevel);
    }

    public function addmaster(){
        $sale = new Sale();
        $sale->TrxNo = 0;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $sale->OutletId = $this->userOutletId;
            $sale->TrxTime = date('Y-m-d h:i:s');
            $sale->CustCode = $this->GetPostValue("CustCode");
            $sale->Notes = $this->GetPostValue("Notes");
            $sale->TableNo = $this->GetPostValue("TableNo");
            $sale->MoneyAmt = 0;
            $sale->PayAmt = 0;
            $sale->TrxStatus = 0;
            $sale->TrxNo = $sale->AutoTrxNo($this->userOutletId,$sale->TrxTime);
            $sale->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($sale->Insert() == 1) {
                $result['error'] = '';
                $result['result'] = 1;
                print($sale->TrxNo);
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Sale -> Nama: '.$sale->Nama.' - '.$sale->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Trx No: '%s' telah ada pada database !", $sale->TrxNo);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
                print("0");
            }
        }else{
            print("0");
        }
    }
    
    public function addetail(){
        require_once(MODEL . "master/produk.php");
        //$log = new UserAdmin();
        $saledetail = new SaleDetail();
        $items = null;
        $is_item_exist = false;
        if (count($this->postData) > 0) {
            $saledetail->OutletId = $this->userOutletId;
            $saledetail->TrxNo = $this->GetPostValue("trx_no");
            $saledetail->Sku = $this->GetPostValue("sku");
            $saledetail->Qty = $this->GetPostValue("qty");
            $saledetail->Harga = 0;
            $saledetail->Diskon = 0;
            $produk = new Produk();
            $produk = $produk->FindBySku($saledetail->Sku,$this->userOutletId);
            if ($produk != null) {
                $saledetail->Harga = $produk->HrgJual;
                if ($saledetail->Harga == 0){
                    print('ER|Harga produk belum diisi!');
                }else{
                    // periksa apa sudah ada item dengan harga yang sama, kalo ada gabungkan saja
                    $saledetail_exists = new SaleDetail();
                    $saledetail_exists = $saledetail_exists->FindDuplicate($saledetail->TrxNo,$saledetail->Sku,$this->userOutletId);
                    if ($saledetail_exists != null){
                        // proses penggabungan disini
                        /** @var $saledetail_exists SaleDetail */
                        $is_item_exist = true;
                        $saledetail->Qty+= $saledetail_exists->Qty;
                        $saledetail->Diskon+= $saledetail_exists->Diskon;
                    }
                    // insert ke table
                    if ($is_item_exist){
                        // sudah ada item yg sama gabungkan..
                        if ($saledetail->Qty < 1){
                            $rs = $saledetail->Delete($saledetail_exists->Id);
                        }else {
                            $rs = $saledetail->Update($saledetail_exists->Id);
                        }
                        if ($rs > 0) {
                            print('OK|Proses simpan update berhasil!');
                        } else {
                            print('ER|Gagal proses update data!');
                        }
                    }else {
                        // item baru simpan
                        $rs = $saledetail->Insert() == 1;
                        if ($rs > 0) {
                            print('OK|Proses simpan data berhasil!');
                        } else {
                            print('ER|Gagal proses simpan data!');
                        }
                    }
                }
            }else{
                print('ER|Data Produk tidak ditemukan!');
            }
        }else{
            print('ER|No Data posted!');
        }
    }

    public function bayar(){
        require_once (MODEL . "trx/kas.php");
        $txn = $_POST['trx_no'];
        $sale = new Sale();
        $sale = $sale->FindByTrxNo($txn);
        $sale->SubTotal = $_POST['sub_total'];
        $sale->TableNo = $_POST['table_no'];
        $sale->Notes = $_POST['notes'];
        $sale->MoneyAmt = $_POST['pay_amt'];
        $sale->IsTaxable = $_POST['is_tax'];
        $sale->TaxPct = $_POST['tax_pct'];
        $sale->TaxAmt = $_POST['tax_amt'];
        $sale->DiscPct = $_POST['disc_pct'];
        $sale->DiscAmt = $_POST['disc_amt'];
        $sale->PayAmt = $_POST['grand_total'];
        $sale->TrxStatus = 1;
        $sale->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
        if ($sale->Update($sale->Id)== 1){
            //posting ke transaksi kas
            $kas = new Kas();
            $kas->OutletId = $this->userOutletId;
            $kas->TrxDate = strtotime($sale->TrxTime);
            $kas->TrxType = 2;
            $kas->TrxMode = 2;
            $kas->TrxStatus = 2;
            $kas->TrxNo = $kas->AutoTrxNo($this->userOutletId,$kas->TrxDate);
            $kas->Jumlah = $sale->PayAmt;
            $kas->Notes = 'Penjualan No: '.$sale->TrxNo;
            $kas->ReffNo = $sale->TrxNo;
            $kas->CreatebyId = $sale->UpdatebyId;
            $kas->Insert();
            print ('OK');
        }else{
            print('ER');
        }
    }

    public function pending(){
        $txn = $_POST['trx_no'];
        $sale = new Sale();
        $sale = $sale->FindByTrxNo($txn);
        $sale->SubTotal = $_POST['sub_total'];
        $sale->TableNo = $_POST['table_no'];
        $sale->Notes = $_POST['notes'];
        $sale->MoneyAmt = 0;
        $sale->IsTaxable = $_POST['is_tax'];
        $sale->TaxPct = $_POST['tax_pct'];
        $sale->TaxAmt = $_POST['tax_amt'];
        $sale->DiscPct = $_POST['disc_pct'];
        $sale->DiscAmt = $_POST['disc_amt'];
        $sale->PayAmt = $_POST['grand_total'];
        $sale->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
        if ($sale->Update($sale->Id)== 1){
            print ('OK');
        }else{
            print('ER');
        }
    }

    public function delete() {
        require_once (MODEL . "trx/kas.php");
	    $txn = $_POST['trx_no'];
		//$log = new UserAdmin();
		$sale = new Sale();
		$sale = $sale->FindByTrxNo($txn);
		$rfno = null;
		if ($sale == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    $rfno = $sale->TrxNo;
		    if ($sale->TrxStatus == 0) {
                if ($sale->Delete($sale->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Sale -> Nama: '.$sale->Nama.' - '.$sale->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Sale -> Nama: '.$sale->Nama.' - '.$sale->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $sale->TrxNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }elseif ($sale->TrxStatus == 1 || $sale->TrxStatus == 2) {
                if ($sale->Void($sale->Id) == 1) {
                    //hapus transaksi kas
                    $kas = new Kas();
                    $kas = $kas->FindByReffNo($rfno);
                    $kid = null;
                    if ($kas != null){
                        $kid = $kas->Id;
                        $kas->Delete($kid);
                    }
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Sale -> Nama: '.$sale->Nama.' - '.$sale->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Sale -> Nama: '.$sale->Nama.' - '.$sale->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $sale->TrxNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }
        }
		print json_encode($result);
	}

	public function getJsonSale(){
        $sale = new Sale();
        //if ($this->userLevel > 3) {
        //    $saleLists = $sale->GetJsonSales(0,true);
        //}else{
        $saleLists = $sale->GetJsonSales($this->userLevel,$this->userOutletId,date('Y-m-d'));
        //}
	    print json_encode($saleLists);
    }

    public function getJsonSaleDetail(){
	    $trxNo = $_POST["trx_no"];
        $dsale = new SaleDetail();
        $dsale = $dsale->GetJsonSaleDetail($trxNo);
        print json_encode($dsale);
    }

    public function getSubTotal($trxNo){
        $dsale = new SaleDetail();
        $subTotal = $dsale->GetSubTotal($trxNo);
        print($subTotal);
    }

    public function getTrxData(){
        $trx_no = $_POST["trx_no"];
        $sale = new Sale();
        $sale = $sale->FindByTrxNo($trx_no);
        $data = array();
        if ($sale != null){
            $data['id'] = $sale->Id;
            $data['trx_time'] = $sale->TrxTime;
            $data['table_no'] = $sale->TableNo;
            $data['cust_code'] = $sale->CustCode;
            $data['cust_name'] = $sale->CustName;
            $data['outlet_kode'] = $sale->OutletKode;
            $data['outlet_name'] = $sale->OutletName;
            $data['outlet_alamat'] = $sale->OutletAlamat;
            $data['sub_total'] = $sale->SubTotal;
            $data['disc_pct'] = $sale->DiscPct;
            $data['disc_amt'] = $sale->DiscAmt;
            $data['tax_pct'] = $sale->TaxPct;
            $data['tax_amt'] = $sale->TaxAmt;
            $data['total_amt'] = $sale->PayAmt;
            $data['cash_amt'] = $sale->MoneyAmt;
            $data['trx_status'] = $sale->TrxStatus;
            $data['dtrx_status'] = $sale->DTrxStatus;
            $data['notes'] = $sale->Notes;
        }
        print json_encode($data);
    }

    public function report(){
        require_once (MODEL . "master/outlet.php");
        $month = (int)date("n");
        $year = (int)date("Y");
        if (count($this->postData) > 0) {
            if ($this->userOutletId == 1) {
                $outletId = $this->GetPostValue("outletId");
            }else{
                $outletId = $this->userOutletId;
            }
            $startDate = strtotime($this->GetPostValue("startDate"));
            $endDate = strtotime($this->GetPostValue("endDate"));
            $jnsLaporan = $this->GetPostValue("jnsLaporan");
            $outPut = $this->GetPostValue("outPut");
            $sale = new Sale();
            if ($jnsLaporan == 1) {
                $reports = $sale->LoadByOutletId($outletId, $startDate, $endDate);
            }elseif ($jnsLaporan == 2){
                $reports = $sale->Load4RekapDate($outletId, $startDate, $endDate);
            }else{
                $reports = $sale->Load4RekapItem($outletId, $startDate, $endDate);
            }
        }else{
            $outletId = $this->userOutletId;
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $sale = new Sale();
            $reports = $sale->LoadByOutletId($outletId,$startDate,$endDate);
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
        $this->Set("uOutletId",$this->userOutletId);
        $this->Set("startDate",$startDate);
        $this->Set("endDate",$endDate);
        $this->Set("jnsLaporan",$jnsLaporan);
        $this->Set("outPut",$outPut);
        $this->Set("reports",$reports);
    }

    public function getStrukData(){
        $trx_no = $_POST["trx_no"];
        $sale = new Sale();
        $sale = $sale->FindByTrxNo($trx_no);
        $data = array();
        $i = 0;
        if ($sale != null){
            $data[$i]['format'] = 'AC';
            $data[$i]['text'] = '';
            $i++;
            $data[$i]['format'] = 'B1';
            $data[$i]['text'] = $sale->OutletName;
            $i++;
            $data[$i]['format'] = 'B0';
            $data[$i]['text'] = $sale->OutletAlamat;
            $i++;
            $data[$i]['format'] = 'AL';
                               //1234567890123456789012345678901234567890
            $data[$i]['text'] = '----------------------------------------';
            $i++;
            $data[$i]['format'] = 'AL';
            $data[$i]['text'] = '#'.$sale->TrxNo.'  '.$sale->TrxTime.'  T:'.$sale->TableNo;
            $i++;
            $data[$i]['format'] = 'AL';
            $data[$i]['text'] = '----------------------------------------';
            $saledetails = $sale->LoadDetails();
            $tx1 = null;
            $txt = null;
            foreach ($saledetails as $idx => $detail){
                $tx1 = $detail->Qty;
                $tx1 = str_repeat(' ',3-strlen($tx1)).$tx1.' ';
                $txt = $tx1;
                $tx1 = trim($detail->ProdukName).str_repeat(' ',21);
                $tx1 = left($tx1,21);
                $txt.= $tx1;
                $tx1 = $detail->Harga;
                $tx1 = str_repeat(' ',8-strlen($tx1)).$tx1;
                $txt.= $tx1;
                $tx1 = $detail->Qty * $detail->Harga;
                $tx1 = str_repeat(' ',9-strlen($tx1)).$tx1;
                $txt.= $tx1;
                $i++;
                $data[$i]['format'] = 'AL';
                $data[$i]['text'] = $txt;
            }
            $i++;
            $data[$i]['format'] = 'AL';
            $data[$i]['text'] = '----------------------------------------';

            $i++;
            $data[$i]['format'] = 'AR';
            $data[$i]['text'] = 'Total Rp. '.$sale->SubTotal;
            $i++;
            $data[$i]['format'] = 'AR';
            $data[$i]['text'] = 'Cash Rp. '.$sale->MoneyAmt;
            $i++;
            $data[$i]['format'] = 'AR';
            $change = $sale->MoneyAmt - $sale->SubTotal;
            $data[$i]['text'] = 'Change Rp. '.$change;
            $i++;
            $data[$i]['format'] = 'AC';
            $data[$i]['text'] = '** TERIMA KASIH ATAS KUNJUNGAN ANDA **';
        }
        print json_encode($data);
    }
}
