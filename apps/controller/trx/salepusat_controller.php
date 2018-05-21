<?php
class SalePusatController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "trx/salepusat.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
	    $this->Set("outletId",$this->userOutletId);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "inventory/supplier.php");
        $salepusat = new SalePusat();
        //$log = new UserAdmin();
        $salepusat->SalePusatNo = 0;
        $salepusat->SalePusatStatus = 0;
        $salepusat->SalePusatDate = date('Y-m-d');
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $suppliers = new Supplier();
        $suppliers = $suppliers->LoadAll();
        $this->Set("suppliers", $suppliers);
        $produks = new Produk();
        $produks = $produks->LoadProdukBahan($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("stokin", $salepusat);
    }

    public function edit($trxNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "inventory/supplier.php");
        $salepusat = new SalePusat();
        //$log = new UserAdmin();
        $salepusat->FindBySalePusatNo($trxNo);
        if ($salepusat == null || $salepusat->SalePusatStatus > 0){
            redirect_url("inventory.stokin");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $suppliers = new Supplier();
        $suppliers = $suppliers->LoadAll();
        $this->Set("suppliers", $suppliers);
        $produks = new Produk();
        $produks = $produks->LoadProdukBahan($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("stokin", $salepusat);
    }

    public function view($trxNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/customer.php");
        $salepusat = new SalePusat();
        //$log = new UserAdmin();
        $salepusat->FindByTrxNo($trxNo);
        if ($salepusat == null){
            redirect_url("trx.salepusat");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $customers = new Customer();
        $customers = $customers->LoadAll();
        $this->Set("customers", $customers);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("salepusat", $salepusat);
    }

    public function addmaster(){
        $salepusat = new SalePusat();
        $salepusat->SalePusatNo = 0;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $salepusat->OutletId = $this->userOutletId;
            $salepusat->SalePusatDate = strtotime($this->GetPostValue("txtSalePusatDate"));
            $salepusat->SuppCode = $this->GetPostValue("txtSuppCode");
            //$salepusat->Notes = $this->GetPostValue("Notes");
            $salepusat->SalePusatStatus = 0;
            $salepusat->SalePusatNo = $salepusat->AutoSalePusatNo($this->userOutletId,$salepusat->SalePusatDate);
            $salepusat->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($salepusat->Insert() == 1) {
                $result['error'] = '';
                $result['result'] = 1;
                print($salepusat->SalePusatNo);
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New SalePusat -> Nama: '.$salepusat->Nama.' - '.$salepusat->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("SalePusat No: '%s' telah ada pada database !", $salepusat->SalePusatNo);
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
        $salepusatdetail = new SalePusatDetail();
        $items = null;
        $is_item_exist = false;
        if (count($this->postData) > 0) {
            $salepusatdetail->OutletId = 1;
            $salepusatdetail->SalePusatNo = $this->GetPostValue("trx_no");
            $salepusatdetail->Sku = $this->GetPostValue("sku");
            $salepusatdetail->QtyTerima = $this->GetPostValue("qty");
            $salepusatdetail->Harga = 0;
            $salepusatdetail->Diskon = 0;
            $produk = new Produk();
            $produk = $produk->FindBySku($salepusatdetail->Sku,$this->userOutletId);
            if ($produk != null) {
                $salepusatdetail->Harga = $produk->HrgJual;
                if ($salepusatdetail->Harga == 0){
                    print('ER|Harga produk belum diisi!');
                }else{
                    // periksa apa sudah ada item dengan harga yang sama, kalo ada gabungkan saja
                    $salepusatdetail_exists = new SalePusatDetail();
                    $salepusatdetail_exists = $salepusatdetail_exists->FindDuplicate($salepusatdetail->SalePusatNo,$salepusatdetail->Sku);
                    if ($salepusatdetail_exists != null){
                        // proses penggabungan disini
                        /** @var $salepusatdetail_exists SalePusatDetail */
                        $is_item_exist = true;
                        $salepusatdetail->QtyTerima+= $salepusatdetail_exists->QtyTerima;
                        $salepusatdetail->Diskon+= $salepusatdetail_exists->Diskon;
                    }
                    // insert ke table
                    if ($is_item_exist){
                        // sudah ada item yg sama gabungkan..
                        if ($salepusatdetail->QtyTerima < 1){
                            $rs = $salepusatdetail->Delete($salepusatdetail_exists->Id);
                        }else {
                            $rs = $salepusatdetail->Update($salepusatdetail_exists->Id);
                        }
                        if ($rs > 0) {
                            print('OK|Proses simpan update berhasil!');
                        } else {
                            print('ER|Gagal proses update data!');
                        }
                    }else {
                        // item baru simpan
                        $rs = $salepusatdetail->Insert() == 1;
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
            print('ER|No Data stokinsted!');
        }
    }

    public function deldetail(){
        $id = $_POST['id'];
        $salepusatd = new SalePusatDetail();
        $salepusatd = $salepusatd->FindById($id);
        if ($salepusatd == null) {
            $result['error'] = 'Data yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($salepusatd->Delete($salepusatd->Id) == 1) {
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $result['error'] = printf("Gagal menghapus Detail PO: '%s'. Message: %s", $salepusatd->SalePusatNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    //proses approval stok in
    public function proses() {
        $txn = $_POST['trx_no'];
        //$log = new UserAdmin();
        $spt = new SalePusat();
        $spt = $spt->FindByTrxNo($txn);
        if ($spt == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($spt->TrxStatus == 0) {
                $uid = AclManager::GetInstance()->GetCurrentUser()->Id;
                if ($spt->Proses($spt->Id,$uid) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$spt->Nama.' - '.$spt->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$spt->Nama.' - '.$spt->Sku,'-','Failed');
                    $result['error'] = printf("Gagal proses approval: '%s'. Message: %s", $spt->SalePusatNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }else{
                $result['error'] = 'Transaksi Penjualan ini sudah diproses!';
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    public function delete() {
	    $txn = $_POST['trx_no'];
		//$log = new UserAdmin();
		$salepusat = new SalePusat();
		$salepusat = $salepusat->FindBySalePusatNo($txn);
		if ($salepusat == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    if ($salepusat->SalePusatStatus == 0) {
                if ($salepusat->Delete($salepusat->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete SalePusat -> Nama: '.$salepusat->Nama.' - '.$salepusat->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete SalePusat -> Nama: '.$salepusat->Nama.' - '.$salepusat->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $salepusat->SalePusatNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }elseif ($salepusat->SalePusatStatus == 1 || $salepusat->SalePusatStatus == 2) {
                if ($salepusat->Void($salepusat->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete SalePusat -> Nama: '.$salepusat->Nama.' - '.$salepusat->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete SalePusat -> Nama: '.$salepusat->Nama.' - '.$salepusat->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $salepusat->SalePusatNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }
        }
		print json_encode($result);
	}

	public function getJsonSalePusat(){
        $salepusat = new SalePusat();
        $salepusatLists = $salepusat->GetJsonSalePusat($this->userOutletId);
	    print json_encode($salepusatLists);
    }

    public function getJsonSalePusatDetail(){
	    $trxNo = $_POST["trx_no"];
        $dsalepusat = new SalePusatDetail();
        $dsalepusat = $dsalepusat->GetJsonSalePusatDetail($trxNo);
        print json_encode($dsalepusat);
    }

    public function getSubTotal($trxNo){
        $salepusat = new SalePusat();
        $salepusat = $salepusat->FindBySalePusatNo($trxNo);
        if ($salepusat != null){
            print($salepusat->SubTotal);
        }else{
            print('0');
        }

    }

    public function report(){
        require_once (MODEL . "master/outlet.php");
        $month = (int)date("n");
        $year = (int)date("Y");
        if (count($this->postData) > 0) {
            $outletKode =  $this->GetPostValue("outletKode");
            $startDate = strtotime($this->GetPostValue("startDate"));
            $endDate = strtotime($this->GetPostValue("endDate"));
            $jnsLaporan = $this->GetPostValue("jnsLaporan");
            $outPut = $this->GetPostValue("outPut");
            $salepusat = new SalePusat();
            $reports = $salepusat->Load4Report($outletKode,$startDate,$endDate);
        }else{
            $outletKode = '0';
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $salepusat = new SalePusat();
            $reports = $salepusat->Load4Report($outletKode,$startDate,$endDate);
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
        $this->Set("outletKode",$outletKode);
        $this->Set("startDate",$startDate);
        $this->Set("endDate",$endDate);
        $this->Set("jnsLaporan",$jnsLaporan);
        $this->Set("outPut",$outPut);
        $this->Set("reports",$reports);
    }
}
