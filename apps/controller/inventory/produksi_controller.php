<?php
class ProduksiController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/produksi.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
	    //load data to datatables
        $this->Set("outletId",$this->userOutletId);
        $this->Set("userLevel", $this->userLevel);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        $produksi = new Produksi();
        //$log = new UserAdmin();
        $produksi->ProdNo = 0;
        $produksi->ProdStatus = 0;
        $produksi->ProdDate = date('Y-m-d');
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $produks = new Produk();
        $produks = $produks->LoadProdukMentah($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("produksi", $produksi);
        $this->Set("userLevel", $this->userLevel);
    }

    public function edit($prodNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        $produksi = new Produksi();
        //$log = new UserAdmin();
        $produksi->FindByProdNo($prodNo);
        if ($produksi == null || $produksi->ProdStatus > 0){
            redirect_url("inventory.produksi");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $produks = new Produk();
        $produks = $produks->LoadProdukMentah($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("produksi", $produksi);
        $this->Set("userLevel", $this->userLevel);
    }

    public function view($prodNo = null) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/produk.php");
        $produksi = new Produksi();
        //$log = new UserAdmin();
        $produksi->FindByProdNo($prodNo);
        if ($produksi == null){
            redirect_url("inventory.produksi");
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets", $outlets);
        $produks = new Produk();
        $produks = $produks->LoadProdukMentah($this->userOutletId);
        $this->Set("produks", $produks);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("produksi", $produksi);
        $this->Set("userLevel", $this->userLevel);
    }

    public function addmaster(){
        $produksi = new Produksi();
        $produksi->ProdNo = 0;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $produksi->OutletId = $this->userOutletId;
            $produksi->ProdDate = strtotime($this->GetPostValue("txtProdDate"));
            $produksi->Notes = $this->GetPostValue("txtNotes");
            if ($produksi->Notes == null){
                $produksi->Notes = 'Produksi';
            }
            $produksi->ProdStatus = 0;//$this->GetPostValue("txtProdStatus");
            $produksi->ProdNo = $produksi->AutoProdNo($this->userOutletId,$produksi->ProdDate);
            $produksi->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($produksi->Insert() == 1) {
                $result['error'] = '';
                $result['result'] = 1;
                print($produksi->ProdNo);
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Po -> Nama: '.$produksi->Nama.' - '.$produksi->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Produksi No: '%s' telah ada pada database !", $produksi->ProdNo);
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
        $produksidetail = new ProduksiDetail();
        $items = null;
        $is_item_exist = false;
        if (count($this->postData) > 0) {
            $produksidetail->ProdNo = $this->GetPostValue("prod_no");
            $produksidetail->Sku = $this->GetPostValue("sku");
            $produksidetail->Qty = $this->GetPostValue("qty");
            $produksidetail->ProdType = 1;
            $produksidetail->Harga = 0;
            $produk = new Produk();
            $produk = $produk->FindBySku($produksidetail->Sku,$this->userOutletId);
            if ($produk != null) {
                $produksidetail->Harga = $produk->HrgBeli;
                if ($produksidetail->Harga == 0){
                    print('ER|Harga produk belum diisi!');
                }else{
                    $prodno = $produksidetail->ProdNo;
                    $protype = $produksidetail->ProdType;
                    $prosku = $produksidetail->Sku;
                    // periksa apa sudah ada item dengan harga yang sama, kalo ada gabungkan saja
                    $produksidetail_exists = new ProduksiDetail();
                    $produksidetail_exists = $produksidetail_exists->FindDuplicate($this->userOutletId,$prodno,$protype,$prosku);
                    if ($produksidetail_exists != null){
                        // proses penggabungan disini
                        /** @var $produksidetail_exists ProduksiDetail */
                        $is_item_exist = true;
                        $produksidetail->Qty+= $produksidetail_exists->Qty;
                    }
                    // insert ke table
                    if ($is_item_exist){
                        // sudah ada item yg sama gabungkan..
                        if ($produksidetail->Qty < 1){
                            $rs = $produksidetail->Delete($produksidetail_exists->Id);
                        }else {
                            $rs = $produksidetail->Update($produksidetail_exists->Id);
                        }
                        if ($rs > 0) {
                            print('OK|Proses simpan data berhasil!');
                        } else {
                            print('ER|Gagal proses update data!');
                        }
                    }else {
                        // item baru simpan
                        $rs = $produksidetail->Insert() == 1;
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
        $produksid = new ProduksiDetail();
        $produksid = $produksid->FindById($id);
        if ($produksid == null) {
            $result['error'] = 'Data yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($produksid->Delete($produksid->Id) == 1) {
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $result['error'] = printf("Gagal menghapus Bahan Produksi: '%s'. Message: %s", $produksid->ProdNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    //hapus po
    public function delete() {
        $produksin = $_POST['prod_no'];
        //$log = new UserAdmin();
        $produksi = new Produksi();
        $produksi = $produksi->FindByProdNo($produksin);
        if ($produksi == null) {
            $result['error'] = 'Data Produksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($produksi->ProdStatus == 0) {
                if ($produksi->Delete($produksi->Id) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$produksi->Nama.' - '.$produksi->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$produksi->Nama.' - '.$produksi->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Produksi: '%s'. Message: %s", $produksi->ProdNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }elseif ($produksi->ProdStatus == 1 || $produksi->ProdStatus == 2) {
                if ($produksi->Void($produksin) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$produksi->Nama.' - '.$produksi->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$produksi->Nama.' - '.$produksi->Sku,'-','Failed');
                    $result['error'] = printf("Gagal menghapus Data Produksi: '%s'. Message: %s", $produksi->ProdNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }
        }
        print json_encode($result);
    }

    //proses approval produksi
    public function proses() {
        $prodno = $_POST['prod_no'];
        //$log = new UserAdmin();
        $produksi = new Produksi();
        $produksi = $produksi->FindByProdNo($prodno);
        if ($produksi == null) {
            $result['error'] = 'Data Transaksi yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($produksi->ProdStatus == 0) {
                if ($produksi->Proses($prodno) == 1) {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Success');
                    $result['error'] = '';
                    $result['result'] = 1;
                } else {
                    //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Po -> Nama: '.$po->Nama.' - '.$po->Sku,'-','Failed');
                    $result['error'] = printf("Gagal proses approval: '%s'. Message: %s", $produksi->ProdNo, $this->connector->GetErrorMessage());
                    $result['result'] = 0;
                }
            }else{
                $result['error'] = 'Produksi ini sudah diproses!';
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

	public function getJsonProduksi(){
        $produksi = new Produksi();
        $prodLists = $produksi->GetJsonProduksis($this->userOutletId);
	    print json_encode($prodLists);
    }

    public function getJsonBahan(){
	    $prodNo = $_POST["prod_no"];
        $dprod = new ProduksiDetail();
        $dprod = $dprod->GetJsonProduksiDetail($prodNo,1);
        print json_encode($dprod);
    }

    public function getJsonHasil(){
        $prodNo = $_POST["prod_no"];
        $dprod = new ProduksiDetail();
        $dprod = $dprod->GetJsonProduksiDetail($prodNo,2);
        print json_encode($dprod);
    }

    public function getSubTotal($prodNo){
        $prod = new Produksi();
        $prod = $prod->FindByProdNo($prodNo);
        if ($prod != null){
            print($prod->SubTotal);
        }else{
            print('0');
        }

    }
}
