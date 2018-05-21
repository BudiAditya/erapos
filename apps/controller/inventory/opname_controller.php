<?php
class OpnameController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/opname.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
        require_once(MODEL . "master/produk.php");
		//load data to datatables
        $bahan = new Produk();
        $bahan = $bahan->LoadProdukBahan($this->userOutletId);
        $this->Set("bahan",$bahan);
        $this->Set("userLevel",$this->userLevel);
        $this->Set("outletId",$this->userOutletId);
	}

	public function add() {
        $opname = new Opname();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $opname->OutletId = $this->userOutletId;
            $opname->Tanggal = $this->GetPostValue("Tanggal");
            $opname->OpType = $this->GetPostValue("OpType");
            $opname->Sku = $this->GetPostValue("Sku");
            $opname->Qty = $this->GetPostValue("Qty");
            $opname->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($opname->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'inventory.opname','Add New Opname -> '.$opname->Tanggal.' SKU:'.$opname->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("%s - Data yang sama sudah ada di database !", $opname->Sku);
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

    public function edit() {
        $opname = new Opname();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $result['error'] = '';
        $result['result'] = 0;
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $opname->Id = $this->GetPostValue("Id");
            $opname->OutletId = $this->userOutletId;
            $opname->Tanggal = $this->GetPostValue("Tanggal");
            $opname->OpType = $this->GetPostValue("OpType");
            $opname->Sku = $this->GetPostValue("Sku");
            $opname->Qty = $this->GetPostValue("Qty");
            $opname->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($opname->Update($opname->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'inventory.opname','Update Data Opname -> '.$opname->Tanggal.' SKU:'.$opname->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("%s - Data yg sama telah ada pada database !", $opname->Sku);
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
	    $opname = new Opname();
	    $opname = $opname->LoadById($id);
	    $data = array();
	    if ($opname != null){
            $data['id'] = $opname->Id;
            $data['tanggal'] = $opname->Tanggal;
            $data['op_type'] = $opname->OpType;
            $data['sku'] = $opname->Sku;
            $data['qty'] = $opname->Qty;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$opname = new Opname();
		$opname = $opname->FindById($id);
		if ($opname == null) {
            $result['error'] = 'Data Opname yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($opname->Delete($opname->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'inventory.opname','Update Data Opname -> '.$opname->Tanggal.' SKU:'.$opname->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $result['error'] = printf("Gagal menghapus data outlet: '%s'. Message: %s", $opname->Opname, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonOpname(){
	    $opname = new Opname();
	    $opnameLists = $opname->GetJsonOpname($this->userOutletId);
	    print json_encode($opnameLists);
    }
}
