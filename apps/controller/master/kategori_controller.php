<?php
class KategoriController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "master/kategori.php");
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

	public function add() {
        $kategori = new Kategori();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $kategori->EntityId = 1;
            $kategori->Kategori = $this->GetPostValue("Kategori");
            $kategori->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($kategori->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.kategori','Add New Kategori -> '.$kategori->Kategori,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.kategori','Add New Kategori ->  '.$kategori->Kategori,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $kategori->Kategori);
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
        $kategori = new Kategori();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $result['error'] = '';
        $result['result'] = 0;
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $kategori->Id = $this->GetPostValue("Id");
            $kategori->Kategori = $this->GetPostValue("Kategori");
            $kategori->EntityId = 1;
            $kategori->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($kategori->Update($kategori->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Kategori -> '.$kategori->Kategori,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Kategori -> '.$kategori->Kategori,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $kategori->Kategori);
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
	    $kategori = new Kategori();
	    $kategori = $kategori->LoadById($id);
	    $data = array();
	    if ($kategori != null){
            $data['id'] = $kategori->Id;
            $data['kategori'] = $kategori->Kategori;
            $data['entity_id'] = $kategori->EntityId;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$kategori = new Kategori();
		$kategori = $kategori->FindById($id);
		if ($kategori == null) {
            $result['error'] = 'Data Kategori yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($kategori->Delete($kategori->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.kategori','Delete Kategori -> '.$kategori->Kategori,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.kategori','Delete Kategori -> '.$kategori->Kategori,'-','Failed');
                $result['error'] = printf("Gagal menghapus data outlet: '%s'. Message: %s", $kategori->Kategori, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonKategori(){
	    $kategori = new Kategori();
	    $kategoriLists = $kategori->GetJsonKategori($this->userCompanyId);
	    print json_encode($kategoriLists);
    }
}
