<?php
class StokInController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/stokin.php");
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
        $stokin = new StokIn();
        //$log = new UserAdmin();
        $stokin->StokInNo = 0;
        $stokin->StokInStatus = 0;
        $stokin->StokInDate = date('Y-m-d');
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
        $this->Set("stokin", $stokin);
    }

    public function edit($stokInNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "inventory/supplier.php");
        $stokin = new StokIn();
        //$log = new UserAdmin();
        $stokin->FindByStokInNo($stokInNo);
        if ($stokin == null || $stokin->StokInStatus > 0){
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
        $this->Set("stokin", $stokin);
    }

    public function view($stokInNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "inventory/supplier.php");
        $stokin = new StokIn();
        //$log = new UserAdmin();
        $stokin->FindByStokInNo($stokInNo);
        if ($stokin == null){
            redirect_url("inventory.stokin");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $suppliers = new Supplier();
        $suppliers = $suppliers->LoadAll();
        $this->Set("suppliers", $suppliers);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("stokin", $stokin);
    }

    public function addmaster(){
        $stokin = new StokIn();
        $stokin->StokInNo = 0;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $stokin->OutletId = $this->userOutletId;
            $stokin->StokInDate = strtotime($this->GetPostValue("txtStokInDate"));
            $stokin->SuppCode = $this->GetPostValue("txtSuppCode");
            //$stokin->Notes = $this->GetPostValue("Notes");
            $stokin->StokInStatus = 0;
            $stokin->StokInNo = $stokin->AutoStokInNo($this->userOutletId,$stokin->StokInDate);
            $stokin->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($stokin->Insert() == 1) {
                $result['error'] = '';
                $result['result'] = 1;
                print($stokin->StokInNo);
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New StokIn -> Nama: '.$stokin->Nama.' - '.$stokin->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("StokIn No: '%s' telah ada pada database !", $stokin->StokInNo);
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
        $stokindetail = new StokInDetail();
        $items = null;
        $is_item_exist = false;
        if (count($this->postData) > 0) {
            $stokindetail->OutletId = $this->userOutletId;
            $stokindetail->StokInNo = $this->GetPostValue("stokin_no");
            $stokindetail->Sku = $this->GetPostValue("sku");
            $stokindetail->QtyTerima = $this->GetPostValue("qty");
            $stokindetail->Harga = 0;
            $stokindetail->Diskon = 0;
            $produk = new Produk();
            $produk = $produk->FindBySku($stokindetail->Sku,$this->userOutletId);
            if ($produk != null) {
                if ($this->userOutletId == 1) {
                    $stokindetail->Harga = $produk->HrgJual;
                }else{
                    $stokindetail->Harga = $produk->HrgBeli;
                }
                if ($stokindetail->Harga == 0){
                    print('ER|Harga produk belum diisi!');
                }else{
                    // periksa apa sudah ada item dengan harga yang sama, kalo ada gabungkan saja
                    $stokindetail_exists = new StokInDetail();
                    $stokindetail_exists = $stokindetail_exists->FindDuplicate($stokindetail->StokInNo,$stokindetail->Sku);
                    if ($stokindetail_exists != null){
                        // proses penggabungan disini
                        /** @var $stokindetail_exists StokInDetail */
                        $is_item_exist = true;
                        $stokindetail->QtyTerima+= $stokindetail_exists->QtyTerima;
                        $stokindetail->Diskon+= $stokindetail_exists->Diskon;
                    }
                    // insert ke table
                    if ($is_item_exist){
                        // sudah ada item yg sama gabungkan..
                        if ($stokindetail->QtyTerima < 1){
                            $rs = $stokindetail->Delete($stokindetail_exists->Id);
                        }else {
                            $rs = $stokindetail->Update($stokindetail_exists->Id);
                        }
                        if ($rs > 0) {
                            print('OK|Proses simpan update berhasil!');
                        } else {
                            print('ER|Gagal proses update data!');
                        }
                    }else {
                        // item baru simpan
                        $rs = $stokindetail->Insert() == 1;
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
        $stokind = new StokInDetail();
        $stokind = $stokind->FindById($id);
        if ($stokind == null) {
            $result['error'] = 'Data yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($stokind->Delete($stokind->Id) == 1) {
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $result['error'] = printf("Gagal menghapus Detail PO: '%s'. Message: %s", $stokind->StokInNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    //proses approval stok in
    public function proses() {
        $stn = $_POST['stokin_no'];
        //$log = new UserAdmin();
        $sti = new StokIn();
        $sti = $sti->FindByStokInNo($stn);
        if ($sti == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($sti->StokInStatus == 0) {
                $uid = AclManager::GetInstance()->GetCurrentUser()->Id;
                if ($sti->Proses($sti->Id,$uid) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$sti->Nama.' - '.$sti->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$sti->Nama.' - '.$sti->Sku,'-','Failed');
                    $result['error'] = printf("Gagal proses approval: '%s'. Message: %s", $sti->StokInNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }else{
                $result['error'] = 'Bukti Penerimaan ini sudah diproses!';
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    public function delete() {
	    $txn = $_POST['stokin_no'];
		//$log = new UserAdmin();
		$stokin = new StokIn();
		$stokin = $stokin->FindByStokInNo($txn);
		if ($stokin == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    if ($stokin->StokInStatus == 0) {
                if ($stokin->Delete($stokin->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete StokIn -> Nama: '.$stokin->Nama.' - '.$stokin->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete StokIn -> Nama: '.$stokin->Nama.' - '.$stokin->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $stokin->StokInNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }elseif ($stokin->StokInStatus == 1 || $stokin->StokInStatus == 2) {
                if ($stokin->Void($stokin->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete StokIn -> Nama: '.$stokin->Nama.' - '.$stokin->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete StokIn -> Nama: '.$stokin->Nama.' - '.$stokin->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $stokin->StokInNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }
        }
		print json_encode($result);
	}

	public function getJsonStokIn(){
        $stokin = new StokIn();
        //if ($this->userLevel > 3) {
        //    $stokinLists = $stokin->GetJsonStokIns(0,true);
        //}else{
            $stokinLists = $stokin->GetJsonStokIns($this->userOutletId);
        //}
	    print json_encode($stokinLists);
    }

    public function getJsonStokInDetail(){
	    $stokInNo = $_POST["stokin_no"];
        $dstokin = new StokInDetail();
        $dstokin = $dstokin->GetJsonStokInDetail($stokInNo);
        print json_encode($dstokin);
    }

    public function getSubTotal($stokInNo = null){
        $stokin = new StokIn();
        $stokin = $stokin->FindByStokInNo($stokInNo);
        if ($stokin != null){
            print($stokin->SubTotal);
        }else{
            print('0');
        }

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
            $stokin = new StokIn();
            $reports = $stokin->LoadByOutletId($outletId,$startDate,$endDate);
        }else{
            $outletId = $this->userOutletId;
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $stokin = new StokIn();
            $reports = $stokin->LoadByOutletId($outletId,$startDate,$endDate);
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
}
