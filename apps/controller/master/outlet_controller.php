<?php
class OutletController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "master/outlet.php");
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
        $outlet = new Outlet();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $outlet->EntityId = 1;
            $outlet->Kode = $this->GetPostValue("Kode");
            $outlet->OutletName = $this->GetPostValue("OutletName");
            $outlet->Kota = $this->GetPostValue("Kota");
            $outlet->Alamat = $this->GetPostValue("Alamat");
            $outlet->Pic = $this->GetPostValue("Pic");
            $outlet->Phone = $this->GetPostValue("Phone");
            $outlet->CabStatus = $this->GetPostValue("CabStatus");
            $outlet->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($outlet->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $outlet->Kode);
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
        $outlet = new Outlet();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $outlet->EntityId = 1;
            $outlet->Id = $this->GetPostValue("Id");
            $outlet->Kode = $this->GetPostValue("Kode");
            $outlet->OutletName = $this->GetPostValue("OutletName");
            $outlet->Kota = $this->GetPostValue("Kota");
            $outlet->Alamat = $this->GetPostValue("Alamat");
            $outlet->Pic = $this->GetPostValue("Pic");
            $outlet->Phone = $this->GetPostValue("Phone");
            $outlet->CabStatus = $this->GetPostValue("CabStatus");
            $outlet->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($outlet->Update($outlet->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $outlet->Kode);
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
	    $outlet = new Outlet();
	    $outlet = $outlet->FindById($id);
	    $data = array();
	    if ($outlet != null){
            $data['id'] = $outlet->Id;
            $data['kode'] = $outlet->Kode;
            $data['outlet_name'] = $outlet->OutletName;
            $data['alamat'] = $outlet->Alamat;
            $data['kota'] = $outlet->Kota;
            $data['pic'] = $outlet->Pic;
            $data['phone'] = $outlet->Phone;
            $data['cab_status'] = $outlet->CabStatus;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$outlet = new Outlet();
		$outlet = $outlet->FindById($id);
		if ($outlet == null) {
            $result['error'] = 'Data outlet yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($outlet->Delete($outlet->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$outlet->Kode.' - '.$outlet->OutletName,'-','Failed');
                $result['error'] = printf("Gagal menghapus data outlet: '%s'. Message: %s", $outlet->Kode, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonOutlet(){
	    $outlet = new Outlet();
	    $outletLists = $outlet->GetJsonOutlet($this->userCompanyId);
	    print json_encode($outletLists);
    }
}
