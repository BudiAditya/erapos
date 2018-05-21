<?php
class UseraclController extends AppController {
	private $userCabangId;
	protected function Initialize() {
		require_once(MODEL . "sys/user_admin.php");
		require_once(MODEL . "sys/user_acl.php");
		$this->userCabangId = $this->persistence->LoadState("cabang_id");
	}

	public function edit($uid = 0) {
		$loader = null;
		$skema = null;
		$userlist = null;
		// find user data
		$log = new UserAdmin();
		$userdata = new UserAdmin();
		$userdata = $userdata->FindById($uid);
        $cabid = $userdata->CabangId;
		if (count($this->postData) > 0) {
			// OK user ada kirim data kita proses
			$skema = $this->GetPostValue("hakakses");
			$prevResId = null;
			$hak = null;
			$cbi = $cabid;
			$userAcl = new UserAcl();
            $userAcl->Delete($uid, $cabid);
            foreach ($skema As $aturan) {
                $tokens = explode("|", $aturan);
                $resid = $tokens[0];
                $hak = $tokens[1];
                if ($prevResId != $resid) {
                    if ($userAcl->Rights != "") {
                        $userAcl->Insert();
                    }
                    $prevResId = $resid;
                    $userAcl = new UserAcl();
                    $userAcl->ResourceId = $resid;
                    $userAcl->UserUid = $uid;
                    $userAcl->CabangId = $cabid;
                    $userAcl->Rights = "";
                }
                $userAcl->Rights .= $hak;
            }
            if ($userAcl->Rights != "") {
                $userAcl->Insert();
                $log = $log->UserActivityWriter($this->userCabangId,'sys.useracl','Setting User ACL -> User: '.$userdata->UserId.' - '.$userdata->UserName,'-','Success');
            }
            $this->persistence->SaveState("info", sprintf("Data Hak Akses User: '%s' telah berhasil disimpan.", $userdata->UserId));
            redirect_url("sys.useradmin");
		} else {
			$userAcl = new UserAcl();
			$hak = $userAcl->LoadAcl($uid,$this->userCabangId);
		}
		// load resource data
		$loader = new UserAcl();
		$resources = $loader->LoadAllResources();
		$this->Set("resources", $resources);
		$this->Set("userdata", $userdata);
		$this->Set("userlist", $userlist);
		$this->Set("hak", $hak);
	}

	public function copy($uid = null) {
		$srcUid = null;
		$cbi = 0;
		if (count($this->postData) > 0) {
			// OK user ada kirim data kita proses
			$cdata = $this->GetPostValue("copyFrom");
			$cbi = $this->GetPostValue("tCabangId");
			$cdata = explode("|",$cdata);
			$srcUid = $cdata[0];
			$srcCbi = $cdata[1];
			$userAcl = new UserAcl();
			$userAcl->Delete($uid,$cbi);
			$userAcl->Copy($srcUid,$srcCbi,$uid,$cbi);
			$this->persistence->SaveState("info", sprintf("Data Hak Akses telah berhasil disalin.."));
			Dispatcher::RedirectUrl("sys.useracl/add/".$uid."/".$cbi);
		} else {
			$userAcl = new UserAcl();
			$hak = $userAcl->LoadAcl($uid,$cbi);
			Dispatcher::RedirectUrl("sys.useracl/add/".$uid."/".$cbi);
		}
	}


}
