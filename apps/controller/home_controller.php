<?php

class HomeController extends AppController {

	protected function Initialize() { }

	public function index() {
		redirect_url("home/login");
	}

	//membuat form tambah data dan proses cek data login
	public function login() {
        require_once(MODEL . "sys/user_admin.php");
        $this->Set("title", "Login"); //set title form
        $error = null;
        // Cek apakah user mengirimkan data username dan password atau tidak
        if (count($this->postData) > 0) {
            // User mengirim data username dan password melalui form login
            //$useroutlet = $this->GetPostValue("user_outlet_id");
            $uemail = trim($this->GetPostValue("user_email"));
            $upaswd = md5($this->GetPostValue("user_paswd"));
            $month = (int)date("n");
            $year = (int)date("Y");
            //jika login berhasil
            if ($this->doLogin($uemail, $upaswd, 0)) {
                $acl = AclManager::GetInstance(); //load class acl untuk session user id
                $uid = $acl->CurrentUser->Id;
                $router = Router::GetInstance();
                $userAdmin = new UserAdmin();
                $userAdmin = $userAdmin->FindById($uid);
                if ($userAdmin == null){
                    $error = "User data invalid!";
                    $this->forcelogout();
                }
                // update table sys_users dengan info login
                $userAdmin->Status = "6";
                $userAdmin->LoginTime = date('Y-m-d H:i:s');
                $userAdmin->LoginFrom = $router->IpAddress;
                $userAdmin->SessionId = $this->persistence->GetPersistenceId();
                $userAdmin->LoginRecord($userAdmin->UserUid);
                // ambil data entity dan project user yang login simpan ke session
                $this->persistence->SaveState("entity_id", $userAdmin->EntityId);
                $this->persistence->SaveState("entity_cd", $userAdmin->EntityCd);
                $this->persistence->SaveState("entity_name", $userAdmin->CompanyName);
                $this->persistence->SaveState("outlet_id", $userAdmin->OutletId);
                $this->persistence->SaveState("outlet_kode", $userAdmin->OutletKode);
                $this->persistence->SaveState("outlet_name", $userAdmin->OutletName);
                $this->persistence->SaveState("outlet_type", $userAdmin->OutletType);
                $this->persistence->SaveState("user_lvl", $userAdmin->UserLvl);
                $this->persistence->SaveState("user_pic", $userAdmin->Fphoto);
                $this->persistence->SaveState("acc_year", $year);
                $this->persistence->SaveState("acc_month", $month);
                //$log = $userAdmin->LoginActivityWriter($useroutlet, $uemail, 'Login success');
                //$log = $userAdmin->UserActivityWriter($useroutlet, 'home.login', 'LogIn to System', '', 'Success');
                Dispatcher::RedirectUrl("main");
            } else {
                //$userAdmin = new UserAdmin();
                //$log = $userAdmin->LoginActivityWriter($useroutlet, $uemail, 'User ID atau Password salah');
                $acl = AclManager::GetInstance();
                $rst = $acl->GetAuthenticationResult();
                if ($rst == -1){
                    $error = "Invalid email!";
                }elseif ($rst == -2){
                    $error = "Invalid password!";
                }elseif ($rst == -3){
                    $error = "Inactive user!";
                }
            }
        }else{
            $acl = AclManager::GetInstance();
            // Kita cek apakah user sudah login atau belum
            if ($acl->GetIsUserAuthenticated()) {
                // User sudah login ke system maka tidak perlu login lagi
                Dispatcher::RedirectUrl("main");
            }
            if ($this->persistence->StateExists("acc_year")) {
                $year = $this->persistence->LoadState("acc_year");
            } else {
                $year = date("Y");
            }
            if ($this->persistence->StateExists("acc_month")) {
                $month = $this->persistence->LoadState("acc_month");
            } else {
                $month = date("n");
            }
        }
        $this->Set("year", $year);
        $this->Set("month", $month);
        $this->Set("error", $error);
	}


	//proses validasi data login
	private function doLogin($uemail, $upaswd, $cabid = 0) {
		$acl = AclManager::GetInstance();
		$success = $acl->Authenticate($uemail, $upaswd, $cabid);
		if ($success) {
			$acl->SerializeUser();
		}
		return $success;
	}

	public function logout() {
		require_once(MODEL . "sys/user_admin.php");
		$acl = AclManager::GetInstance();
		$uid = $acl->CurrentUser->Id;
        $userAdmin = new UserAdmin();
		$userAdmin->Status = "7";
		$userAdmin->LoginTime = date('Y-m-d H:i:s');
		$userAdmin->LoginFrom = trim(getenv("REMOTE_ADDR"));
		$userAdmin->SessionId = null;
		$userAdmin->LoginRecord($uid);
        $log = $userAdmin->UserActivityWriter($this->persistence->LoadState("outlet_id"),'home.login','LogOut From System','','Success');
		$acl->SignOut(); // Logout User yang aktif
		$acl->SerializeUser(); // hapus semua session data
		//$this->persistence->DestroyPersistence;
		Dispatcher::RedirectUrl("home/login");
	}

    public function forcelogout() {
        $acl = AclManager::GetInstance();
        $acl->SignOut(); // Logout User yang aktif
        $acl->SerializeUser(); // hapus semua session data
        //$this->persistence->DestroyPersistence;
        Dispatcher::RedirectUrl("home/login");
    }
}
