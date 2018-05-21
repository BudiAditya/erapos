<?php
class UserAdminController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;
    private $userUid;

	protected function Initialize() {
		require_once(MODEL . "sys/user_admin.php");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
        $this->userUid = AclManager::GetInstance()->GetCurrentUser()->Id;
	}

	public function index() {
	    require_once (MODEL . "master/outlet.php");
        // load data for datatables
        $outlets = new Outlet();
        if ($this->userOutletId == 1) {
            $outlets = $outlets->LoadAll($this->userCompanyId);
        }else{
            $outlets = $outlets->LoadById($this->userOutletId);
        }
        $this->Set("outlets",$outlets);
        $this->Set("outletId",$this->userOutletId);
        $this->Set("userLevel",$this->userLevel);
        $ulevels = new UserAdmin();
        $ulevels = $ulevels->GetUserLevel($this->userLevel,'<=');
        $this->Set("ulevels",$ulevels);
	}

	public function add() {
        require_once(MODEL . "master/outlet.php");
        $useradmin = new UserAdmin();
        //$log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $useradmin->EntityId = $this->userCompanyId;
            $useradmin->OutletId = $this->GetPostValue("OutletId");
            $useradmin->UserName = $this->GetPostValue("UserName");
            $useradmin->UserEmail = $this->GetPostValue("UserEmail");
            $useradmin->UserLvl = $this->GetPostValue("UserLvl");
            $useradmin->UserPwd1 = $this->GetPostValue("UserPwd");
            $useradmin->CreatebyId = $this->userUid;
            $useradmin->IsAktif = $this->GetPostValue("IsAktif");
            $useradmin->Fphoto = $this->GetPostValue("Fphoto");
            if (!empty($_FILES['Fphoto']['tmp_name'])) {
                $fpath = 'public/upload/user-pics/';
                $ftmp = $_FILES['Fphoto']['tmp_name'];
                $fname = $_FILES['Fphoto']['name'];
                $fpath.= $fname;
                if(move_uploaded_file($ftmp,$fpath)){
                    $useradmin->Fphoto = $fpath;
                }
            }else{
                $useradmin->Fphoto = null;
            }
            if ($useradmin->Insert() == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'sys.useradmin','Add New User: '.$useradmin->UserId.' - '.$useradmin->UserName,'-','Success');
                //$this->persistence->SaveState("info", sprintf("Data User: '%s' telah berhasil disimpan.", $useradmin->UserId));
                //redirect_url("sys.useradmin");
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("User Email: '%s' telah ada pada database !", $useradmin->UserEmail);
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
        require_once(MODEL . "master/outlet.php");
        $useradmin = new UserAdmin();
        //$log = new UserAdmin();
        $result['crud'] = 'E';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $useradmin->EntityId = $this->userCompanyId;
            $useradmin->UserUid = $this->GetPostValue("UserUid");
            $useradmin->OutletId = $this->GetPostValue("OutletId");
            $useradmin->UserName = $this->GetPostValue("UserName");
            $useradmin->UserEmail = $this->GetPostValue("UserEmail");
            $useradmin->UserLvl = $this->GetPostValue("UserLvl");
            $useradmin->UserPwd1 = $this->GetPostValue("UserPwd");
            $useradmin->CreatebyId = $this->userUid;
            $useradmin->IsAktif = $this->GetPostValue("IsAktif");
            $useradmin->Fphoto = $this->GetPostValue("Fphoto");
            if (!empty($_FILES['Fphoto']['tmp_name'])) {
                $fpath = 'public/upload/user-pics/';
                $ftmp = $_FILES['Fphoto']['tmp_name'];
                $fname = $_FILES['Fphoto']['name'];
                $fpath.= $fname;
                if(move_uploaded_file($ftmp,$fpath)){
                    $useradmin->Fphoto = $fpath;
                }
            }else{
                $useradmin->Fphoto = null;
            }
            if ($useradmin->Update($useradmin->UserUid) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'sys.useradmin','Add New User: '.$useradmin->UserId.' - '.$useradmin->UserName,'-','Success');
                //$this->persistence->SaveState("info", sprintf("Data User: '%s' telah berhasil disimpan.", $useradmin->UserId));
                //redirect_url("sys.useradmin");
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("User Email: '%s' telah ada pada database !", $useradmin->UserEmail);
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
        $user = new UserAdmin();
        $user = $user->FindById($id);
        $data = array();
        if ($user != null){
            $data['user_uid'] = $user->UserUid;
            $data['is_aktif'] = $user->IsAktif;
            $data['outlet_id'] = $user->OutletId;
            $data['user_name'] = $user->UserName;
            $data['user_level'] = $user->UserLvl;
            $data['user_email'] = $user->UserEmail;
            $data['fphoto'] = $user->Fphoto;
        }
        print json_encode($data);
    }

    public function delete() {
        $id = $_POST['id'];
        //$log = new UserAdmin();
        $user = new UserAdmin();
        $user = $user->FindById($id);
        if ($user == null) {
            $result['error'] = 'Data User yang dipilih tidak ditemukan!';
            $result['result'] = 0;
        }else{
            if ($user->Delete($user->UserUid) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete User -> Nama: '.$user->Nama.' - '.$user->Sku,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'master.produk','Delete User -> Nama: '.$user->Nama.' - '.$user->Sku,'-','Failed');
                $result['error'] = printf("Gagal menghapus Data User: '%s'. Message: %s", $user->UserName, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
        print json_encode($result);
    }

    public function getJsonUsers(){
        $user = new UserAdmin();
        if ($this->userLevel > 3) {
            $userLists = $user->GetJsonUsers($this->userLevel);
        }else{
            $userLists = $user->GetJsonUsers($this->userLevel,$this->userOutletId,$this->userLevel,$this->userUid);
        }
        print json_encode($userLists);
    }
}
