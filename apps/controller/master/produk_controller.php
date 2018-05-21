<?php
class ProdukController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "master/produk.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/kategori.php");
		//load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets",$outlets);
        $kategoris = new Kategori();
        $kategoris = $kategoris->LoadAll($this->userCompanyId);
        $this->Set("kategoris",$kategoris);
        $produks = new Produk();
        if ($this->userLevel > 2) {
            $produks = $produks->LoadAll($this->userOutletId);
        }else{
            $produks = $produks->LoadProdukDijual($this->userOutletId);
        }
        $this->Set("produks",$produks);
        $this->Set("outletId",$this->userOutletId);
        $this->Set("userLevel",$this->userLevel);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/kategori.php");
        $produk = new Produk();
        $log = new UserAdmin();
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $produk->OutletId = $this->userOutletId;
            $produk->KategoriId = $this->GetPostValue("KategoriId");
            $produk->Sku = $this->GetPostValue("Sku");
            $produk->Barcode = $this->GetPostValue("Barcode");
            $produk->Nama = $this->GetPostValue("Nama");
            $produk->Satuan = $this->GetPostValue("Satuan");
            $produk->HrgBeli = $this->GetPostValue("HrgBeli");
            $produk->HrgJual = $this->GetPostValue("HrgJual");
            if (isset($this->postData["IsForsale"])) {
                $produk->IsForsale = 1;
            }else{
                $produk->IsForsale = 0;
            }
            if (isset($this->postData["IsModifier"])) {
                $produk->IsModifier = 1;
            }else{
                $produk->IsModifier = 0;
            }
            if (isset($this->postData["IsResep"])) {
                $produk->IsResep = 1;
            }else{
                $produk->IsResep = 0;
            }
            if (isset($this->postData["IsStock"])) {
                $produk->IsStock = 1;
            }else{
                $produk->IsStock = 0;
            }
            if (isset($this->postData["IsAktif"])) {
                $produk->IsAktif = 1;
            }else{
                $produk->IsAktif = 0;
            }
            $produk->IsShowAll = 0;
            $aoutlets = $this->GetPostValue("AOutlet");
            $aoutlets = implode(",",$aoutlets);
            $produk->AvailableOutlet = $aoutlets;
            $produk->Keterangan = $this->GetPostValue("Keterangan");
            $produk->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            $produk->FPhoto = $this->GetPostValue("FPhoto");
            $ext = null;
            $nfname = null;
            if (!empty($_FILES['FPhoto']['tmp_name'])) {
                $fpath = 'public/upload/produk-pics/';
                $ftmp = $_FILES['FPhoto']['tmp_name'];
                $fname = $_FILES['FPhoto']['name'];
                $ext = end(explode(".",$fname));
                $nfname = $produk->Sku.'.'.$ext;
                $fpath.= $nfname;
                if(move_uploaded_file($ftmp,$fpath)){
                    $produk->FPhoto = $fpath;
                }
            }else{
                $produk->FPhoto = null;
            }
            if ($produk->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
                if ($produk->KategoriId > 2) {
                    redirect_url("master.produk/edit/" . $produk->Id);
                }else {
                    redirect_url("master.produk");
                }
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Produk SKU: '%s' telah ada pada database !", $produk->Sku);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
            }
        }else{
            $produk->OutletId = $this->userOutletId;
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets",$outlets);
        $kategoris = new Kategori();
        $kategoris = $kategoris->LoadAll($this->userCompanyId);
        $this->Set("kategoris",$kategoris);
        $produks = new Produk();
        $produks = $produks->LoadAll();
        $this->Set("produks",$produks);
        $this->Set("outletId",$this->userOutletId);
        $this->Set("produk",$produk);
    }

    public function edit($id) {
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "master/kategori.php");
        require_once(MODEL . "master/modifier.php");
        require_once(MODEL . "master/resep.php");
        $produk = new Produk();
        $log = new UserAdmin();
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $produk->Id = $this->GetPostValue("Id");
            $produk->OutletId = $this->userOutletId;
            $produk->KategoriId = $this->GetPostValue("KategoriId");
            $produk->Sku = $this->GetPostValue("Sku");
            $produk->Barcode = $this->GetPostValue("Barcode");
            $produk->Nama = $this->GetPostValue("Nama");
            $produk->Satuan = $this->GetPostValue("Satuan");
            $produk->HrgBeli = $this->GetPostValue("HrgBeli");
            $produk->HrgJual = $this->GetPostValue("HrgJual");
            if (isset($this->postData["IsForsale"])) {
                $produk->IsForsale = 1;
            }else{
                $produk->IsForsale = 0;
            }
            if (isset($this->postData["IsModifier"])) {
                $produk->IsModifier = 1;
            }else{
                $produk->IsModifier = 0;
            }
            if (isset($this->postData["IsResep"])) {
                $produk->IsResep = 1;
            }else{
                $produk->IsResep = 0;
            }
            if (isset($this->postData["IsStock"])) {
                $produk->IsStock = 1;
            }else{
                $produk->IsStock = 0;
            }
            if (isset($this->postData["IsAktif"])) {
                $produk->IsAktif = 1;
            }else{
                $produk->IsAktif = 0;
            }
            $produk->IsShowAll = 0;
            $produk->AvailableOutlet = 0;
            $produk->Keterangan = $this->GetPostValue("Keterangan");
            $produk->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $produk->FPhoto = $this->GetPostValue("FPhoto");
            $ext = null;
            $nfname = null;
            if (!empty($_FILES['FPhoto']['tmp_name'])) {
                $fpath = 'public/upload/produk-pics/';
                $ftmp = $_FILES['FPhoto']['tmp_name'];
                $fname = $_FILES['FPhoto']['name'];
                $ext = end(explode(".",$fname));
                $nfname = $produk->Sku.'.'.$ext;
                $fpath.= $nfname;
                if(move_uploaded_file($ftmp,$fpath)){
                    $produk->FPhoto = $fpath;
                }
            }else{
                $produk->FPhoto = null;
            }
            if ($produk->Update($produk->Id) == 1) {
                //hapus modifier jika tidak pake modifier
                if ($produk->IsModifier == 0){
                    $modifier = new Modifier();
                    $modifier = $modifier->DelBySkuUtama($produk->Sku,$this->userOutletId);
                }
                //hapus modifier jika tidak pake modifier
                if ($produk->IsResep == 0){
                    $resep = new Resep();
                    $resep = $resep->DelBySkuUtama($produk->Sku,$this->userOutletId);
                }
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
                redirect_url("master.produk");
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Produk SKU: '%s' telah ada pada database !", $produk->Sku);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
            }
        }else{
            $produk = $produk->FindById($id);
        }
        //load data to datatables
        $outlets = new Outlet();
        $outlets = $outlets->LoadAll($this->userCompanyId);
        $this->Set("outlets",$outlets);
        $kategoris = new Kategori();
        $kategoris = $kategoris->LoadAll($this->userCompanyId);
        $this->Set("kategoris",$kategoris);
        $produks = new Produk();
        $produks = $produks->LoadAll();
        $this->Set("produks",$produks);
        $this->Set("outletId",$this->userOutletId);
        $this->Set("produk",$produk);
    }

    public function promodifierlist(){
	    $retprint = '<option value="0" disabled selected="selected"> Pilih Produk </option>';
        $prdjual = new Produk();
        $prdjual = $prdjual->LoadProdukModifier($this->userOutletId);
        foreach ($prdjual as $pro){
            $retprint.= sprintf('<option value="%s">%s - %s</option>', $pro->Sku.'|'.$pro->HrgBeli.'|'.$pro->HrgJual, $pro->Sku, $pro->Nama);
        }
        print($retprint);
    }

    public function probahanlist(){
        $retprint = '<option value="0" disabled selected="selected"> Pilih Bahan </option>';
        $prdbahan = new Produk();
        $prdbahan = $prdbahan->LoadProdukBahan($this->userOutletId);
        foreach ($prdbahan as $pro){
            $retprint.= sprintf('<option value="%s">%s - %s</option>', $pro->Sku.'|'.$pro->HrgBeli.'|'.$pro->HrgJual, $pro->Sku, $pro->Nama);
        }
        print($retprint);
    }

    public function addmodifier() {
	    require_once (MODEL . "master/modifier.php");
        $modifier = new Modifier();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $modifier->OutletId = $this->userOutletId;
            $modifier->SkuUtama = $this->GetPostValue("SkuUtama");
            $modifier->Sku = $this->GetPostValue("Sku");
            $modifier->Qty = $this->GetPostValue("Qty");
            $modifier->Harga = $this->GetPostValue("Harga");
            $modifier->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($modifier->Insert() == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.modifier','Add New Modifer -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Modifier SKU: '%s' telah ada pada database !", $modifier->Sku);
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

    public function editmodifier() {
        require_once (MODEL . "master/modifier.php");
        $modifier = new Modifier();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $modifier->Id = $this->GetPostValue("Id");
            $modifier->OutletId = $this->userOutletId;
            $modifier->SkuUtama = $this->GetPostValue("SkuUtama");
            $modifier->Sku = $this->GetPostValue("Sku");
            $modifier->Qty = $this->GetPostValue("Qty");
            $modifier->Harga = $this->GetPostValue("Harga");
            $modifier->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($modifier->Update($modifier->Id) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.modifier','Add New Modifer -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Modifier SKU: '%s' telah ada pada database !", $modifier->Sku);
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

	public function getmodifier(){
	    require_once (MODEL ."master/modifier.php");
	    $id = $_POST["id"];
	    $modifier = new Modifier();
        $modifier = $modifier->LoadById($id);
	    $data = array();
	    if ($modifier != null){
            $data['id'] = $modifier->Id;
            $data['sku_utama'] = $modifier->SkuUtama;
            $data['sku'] = $modifier->Sku;
            $data['qty'] = $modifier->Qty;
            $data['harga'] = $modifier->Harga;
        }
        print json_encode($data);
    }

    public function delmodifier() {
	    require_once (MODEL . "master/modifier.php");
        $id = $_POST['id'];
        $log = new UserAdmin();
        $modifier = new Modifier();
        $modifier = $modifier->FindById($id);
        if ($modifier == null) {
            $result['error'] = 'Data Modifier yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($modifier->Delete($id) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                $result['error'] = printf("Gagal menghapus Data Modifier: '%s'. Message: %s", $modifier->Sku, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    public function addresep() {
        require_once (MODEL . "master/resep.php");
        $resep = new Resep();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $resep->OutletId = $this->userOutletId;
            $resep->SkuUtama = $this->GetPostValue("SkuUtama");
            $resep->Sku = $this->GetPostValue("Sku");
            $resep->Qty = $this->GetPostValue("Qty");
            $resep->Harga = $this->GetPostValue("Harga");
            $resep->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($resep->Insert() == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.modifier','Add New Modifer -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Resep SKU: '%s' telah ada pada database !", $resep->Sku);
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

    public function editresep() {
        require_once (MODEL . "master/resep.php");
        $resep = new Resep();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $resep->Id = $this->GetPostValue("Id");
            $resep->OutletId = $this->userOutletId;
            $resep->SkuUtama = $this->GetPostValue("SkuUtama");
            $resep->Sku = $this->GetPostValue("Sku");
            $resep->Qty = $this->GetPostValue("Qty");
            $resep->Harga = $this->GetPostValue("Harga");
            $resep->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            if ($resep->Update($resep->Id) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.modifier','Add New Modifer -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Add New Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Resep SKU: '%s' telah ada pada database !", $modifier->Sku);
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

    public function delresep() {
        require_once (MODEL . "master/resep.php");
        $id = $_POST['id'];
        $log = new UserAdmin();
        $resep = new Resep();
        $resep = $resep->FindById($id);
        if ($resep == null) {
            $result['error'] = 'Data Resep yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($resep->Delete($id) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                $result['error'] = printf("Gagal menghapus Data Modifier: '%s'. Message: %s", $resep->Sku, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    public function getresep(){
        require_once (MODEL ."master/resep.php");
        $id = $_POST["id"];
        $resep = new Resep();
        $resep = $resep->LoadById($id);
        $data = array();
        if ($resep != null){
            $data['id'] = $resep->Id;
            $data['sku_utama'] = $resep->SkuUtama;
            $data['sku'] = $resep->Sku;
            $data['qty'] = $resep->Qty;
            $data['harga'] = $resep->Harga;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$produk = new Produk();
		$produk = $produk->FindById($id);
		if ($produk == null) {
            $result['error'] = 'Data Produk yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($produk->Delete($produk->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete Produk -> Nama: '.$produk->Nama.' - '.$produk->Sku,'-','Failed');
                $result['error'] = printf("Gagal menghapus Data Produk: '%s'. Message: %s", $produk->Nama, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonProduk(){
	    $produk = new Produk();
	    if ($this->userLevel > 2) {
            $produkLists = $produk->GetJsonProduk($this->userOutletId);
        }else{
            $produkLists = $produk->GetJsonProdukDijual($this->userOutletId);
        }
	    print json_encode($produkLists);
    }

    public function getJsonProdukModifier($sku){
	    require_once (MODEL . "master/modifier.php");
        $modifier = new Modifier();
        $produkLists = $modifier->GetJsonModifer($sku,$this->userOutletId);
        print json_encode($produkLists);
    }

    public function getJsonProdukResep($sku){
        require_once (MODEL . "master/resep.php");
        $resep = new Resep();
        $produkLists = $resep->GetJsonResep($sku,$this->userOutletId);
        print json_encode($produkLists);
    }

    public function checkSku($sku){
	    $produk = new Produk();
        $produk = $produk->FindBySku($sku,$this->userOutletId);
        if ($produk == null){
            print('OK|0');
        }else{
            print('ER|'.$produk->Nama);
        }
    }

    public function getAutoSku($kategoriId){
        $produk = new Produk();
        $sku = $produk->GetAutoSKU($kategoriId,$this->userOutletId);
        print($sku);
    }

    public function getJsonBahan(){
        $produk = new Produk();
        $produkLists = $produk->GetJsonBahan($this->userOutletId);
        print json_encode($produkLists);
    }
}
