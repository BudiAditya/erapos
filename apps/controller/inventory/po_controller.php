<?php
class PoController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/po.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
	    //load data to datatables
        $this->Set("outletId",$this->userOutletId);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "inventory/supplier.php");
        $po = new Po();
        //$log = new UserAdmin();
        $po->PoNo = 0;
        $po->PoStatus = 0;
        $po->PoDate = date('Y-m-d');
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
        $this->Set("po", $po);
    }

    public function edit($poNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "inventory/supplier.php");
        $po = new Po();
        //$log = new UserAdmin();
        $po->FindByPoNo($poNo);
        if ($po == null || $po->PoStatus > 0){
            redirect_url("inventory.po");
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
        $this->Set("po", $po);
    }

    public function view($poNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "inventory/supplier.php");
        $po = new Po();
        //$log = new UserAdmin();
        $po->FindByPoNo($poNo);
        if ($po == null){
            redirect_url("inventory.po");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $suppliers = new Supplier();
        $suppliers = $suppliers->LoadAll();
        $this->Set("suppliers", $suppliers);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("po", $po);
    }

    public function addmaster(){
        $po = new Po();
        $po->PoNo = 0;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $po->OutletId = $this->userOutletId;
            $po->PoDate = strtotime($this->GetPostValue("txtPoDate"));
            $po->SuppCode = $this->GetPostValue("txtSuppCode");
            //$po->Notes = $this->GetPostValue("Notes");
            $po->PoStatus = 0;
            $po->PoNo = $po->AutoPoNo($this->userOutletId,$po->PoDate);
            $po->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($po->Insert() == 1) {
                $result['error'] = '';
                $result['result'] = 1;
                print($po->PoNo);
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Po No: '%s' telah ada pada database !", $po->PoNo);
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
        $podetail = new PoDetail();
        $items = null;
        $is_item_exist = false;
        if (count($this->postData) > 0) {
            $podetail->OutletId = $this->userOutletId;
            $podetail->PoNo = $this->GetPostValue("po_no");
            $podetail->Sku = $this->GetPostValue("sku");
            $podetail->QtyOrder = $this->GetPostValue("qty");
            $podetail->Harga = 0;
            $podetail->Diskon = 0;
            $produk = new Produk();
            $produk = $produk->FindBySku($podetail->Sku,$this->userOutletId);
            if ($produk != null) {
                if ($this->userOutletId == 1) {
                    $podetail->Harga = $produk->HrgJual;
                }else{
                    $podetail->Harga = $produk->HrgBeli;
                }
                if ($podetail->Harga == 0){
                    print('ER|Harga produk belum diisi!');
                }else{
                    // periksa apa sudah ada item dengan harga yang sama, kalo ada gabungkan saja
                    $podetail_exists = new PoDetail();
                    $podetail_exists = $podetail_exists->FindDuplicate($podetail->PoNo,$podetail->Sku);
                    if ($podetail_exists != null){
                        // proses penggabungan disini
                        /** @var $podetail_exists PoDetail */
                        $is_item_exist = true;
                        $podetail->QtyOrder+= $podetail_exists->QtyOrder;
                        $podetail->Diskon+= $podetail_exists->Diskon;
                    }
                    // insert ke table
                    if ($is_item_exist){
                        // sudah ada item yg sama gabungkan..
                        if ($podetail->QtyOrder < 1){
                            $rs = $podetail->Delete($podetail_exists->Id);
                        }else {
                            $rs = $podetail->Update($podetail_exists->Id);
                        }
                        if ($rs > 0) {
                            print('OK|Proses simpan update berhasil!');
                        } else {
                            print('ER|Gagal proses update data!');
                        }
                    }else {
                        // item baru simpan
                        $rs = $podetail->Insert() == 1;
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

    public function deldetail(){
        $id = $_POST['id'];
        $pod = new PoDetail();
        $pod = $pod->FindById($id);
        if ($pod == null) {
            $result['error'] = 'Data yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($pod->Delete($pod->Id) == 1) {
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $result['error'] = printf("Gagal menghapus Detail PO: '%s'. Message: %s", $pod->PoNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    //proses approval po
    public function proses() {
        require_once (MODEL . "inventory/stokin.php");
        require_once (MODEL . "trx/salepusat.php");
	    $pon = $_POST['po_no'];
		//$log = new UserAdmin();
		$po = new Po();
		$po = $po->FindByPoNo($pon);
		if ($po == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
		    if ($po->PoStatus == 0) {
		        $uid = AclManager::GetInstance()->GetCurrentUser()->Id;
		        $tgl = date('Y-m-d');
		        $sti = new StokIn();
		        $stn = $sti->AutoStokInNo($po->OutletId,$tgl);
		        $spt = new SalePusat();
		        $spn = $spt->AutoTrxNo($this->userOutletId,$tgl);
                if ($po->Proses($po->Id,$stn,$spn,$tgl,$uid) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Failed');
                    $result['error'] = printf("Gagal proses approval: '%s'. Message: %s", $po->PoNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }else{
                $result['error'] = 'PO ini sudah diproses!';
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

    //hapus po
    public function delete() {
        $pon = $_POST['po_no'];
        //$log = new UserAdmin();
        $po = new Po();
        $po = $po->FindByPoNo($pon);
        if ($po == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($po->PoStatus == 0) {
                if ($po->Delete($po->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $po->PoNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }elseif ($po->PoStatus == 1 || $po->PoStatus == 2) {
                if ($po->Void($po->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Transaksi: '%s'. Message: %s", $po->PoNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }
        }
        print json_encode($result);
    }

	public function getJsonPo(){
        $po = new Po();
        if ($this->userLevel > 3) {
            $poLists = $po->GetJsonPos(0,true);
        }else{
            $poLists = $po->GetJsonPos($this->userOutletId);
        }
	    print json_encode($poLists);
    }

    public function getJsonPoDetail(){
	    $poNo = $_POST["po_no"];
        $dpo = new PoDetail();
        $dpo = $dpo->GetJsonPoDetail($poNo);
        print json_encode($dpo);
    }

    public function getSubTotal($poNo){
        $po = new Po();
        $po = $po->FindByPoNo($poNo);
        if ($po != null){
            print($po->SubTotal);
        }else{
            print('0');
        }

    }
}
