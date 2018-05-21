<?php
class UserAdmin extends EntityBase {
	public $UserUid;
	public $IsAktif = 1;
	public $EntityId;
	public $EntityCd;
    public $CompanyName;
	public $OutletId;
	public $OutletKode;
    public $OutletName;
	public $UserName;
	public $UserEmail;
	public $Status = 7;		// By Default Logged Out
	public $LoginTime;
	public $LoginFrom;
	public $UserLvl = 1;
	public $ShortDesc;
	public $AllowMultipleLogin;
	public $UserPwd1;
	public $UserPwd2;
	public $SessionId;
	public $IsForceAccountingPeriod = false;
    public $SysStartDate;
    public $Ulevel;
    public $Fphoto;
    public $CreatebyId;
    public $UpdatebyId;
    public $AreaId;
    public $EmployeeId;
    public $EmpDepId;
    public $AOutletId;
    public $OutletType;

	// Helper Variable
	public function FillProperties(array $row) {
		$this->UserUid = $row["user_uid"];
		$this->IsAktif = $row["is_aktif"];
		$this->EntityId = $row["entity_id"];
		$this->EntityCd = $row["entity_cd"];
        $this->CompanyName = $row["company_name"];
		$this->OutletId = $row["outlet_id"];
		$this->OutletKode = $row["kd_outlet"];
        $this->OutletName = $row["nm_outlet"];
        $this->UserName = $row["user_name"];
		$this->UserEmail = $row["user_email"];
		$this->Status = $row["status"];
		$this->LoginTime = $row["login_time"];
		$this->LoginFrom = $row["login_from"];
		$this->UserLvl = $row["user_lvl"];
		$this->ShortDesc = $row["short_desc"];
		$this->AllowMultipleLogin = $row["allow_multiple_login"];
		$this->UserPwd1 = $row["user_pwd"];
		$this->UserPwd2 = $row["user_pwd"];
        $this->SysStartDate = $row["start_date"];
        $this->Ulevel = $row["ulevel"];
        $this->Fphoto = $row["fphoto"];
        $this->CreatebyId = $row["createby_id"];
        $this->UpdatebyId = $row["updateby_id"];
        $this->AOutletId = $row["a_outlet_id"];
	}

	public function LoadAll($userUid = 0, $userLvl = 5, $orderBy = "a.user_email") {
	    $sqx = "SELECT a.* FROM vw_sys_users AS a'";
	    if ($userUid > 0){
	        if ($userLvl < 5){
	            $sqx.= " Where a.user_lvl < ".$userLvl." Or a.user_uid = ".$userUid;
            }
        }
        $sqx.= " ORDER BY $orderBy";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new UserAdmin();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

	public function FindById($id) {
		$this->connector->CommandText =	"SELECT a.* FROM vw_sys_users AS a WHERE a.user_uid = ?id";
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$row = $rs->FetchAssoc();
		$this->FillProperties($row);
		return $this;
	}

	public function Insert() {
	    $sqx = null;
	    if ($this->Fphoto == null){
            $sqx = "INSERT INTO sys_users(is_aktif, entity_id, outlet_id, user_pwd, user_lvl, user_name, user_email, createby_id, create_time)
VALUES(?is_aktif, ?entity_id, ?outlet_id, ?user_pwd, ?user_lvl, ?user_name, ?user_email, ?createby_id, now())";
        }else{
            $sqx = "INSERT INTO sys_users(is_aktif, entity_id, outlet_id, user_pwd, user_lvl, user_name, user_email, fphoto, createby_id, create_time)
VALUES(?is_aktif, ?entity_id, ?outlet_id, ?user_pwd, ?user_lvl, ?user_name, ?user_email, ?fphoto, ?createby_id, now())";
        }
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?is_aktif", $this->IsAktif);
		$this->connector->AddParameter("?entity_id", $this->EntityId);
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
		$this->connector->AddParameter("?user_pwd", md5($this->UserPwd1));
		$this->connector->AddParameter("?user_lvl", $this->UserLvl);
		$this->connector->AddParameter("?user_name", $this->UserName);
		$this->connector->AddParameter("?user_email", $this->UserEmail);
		$this->connector->AddParameter("?fphoto", $this->Fphoto);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
	    $sqx = null;
        $sqx = 'UPDATE sys_users SET is_aktif = ?is_aktif,entity_id = ?entity_id,outlet_id = ?outlet_id,user_lvl = ?user_lvl,user_name = ?user_name,user_email = ?user_email,updateby_id = ?updateby_id,update_time = now()';
        if ($this->Fphoto != null){
            $sqx.= ', fphoto = ?fphoto';
        }
        if (strlen($this->UserPwd1) > 0){
            $sqx.= ', user_pwd = ?user_pwd';
        }
        $sqx.= ' WHERE user_uid = ?id';
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?is_aktif", $this->IsAktif);
        $this->connector->AddParameter("?entity_id", $this->EntityId);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?a_outlet_id", $this->AOutletId);
        $this->connector->AddParameter("?user_pwd", md5($this->UserPwd1));
        $this->connector->AddParameter("?user_lvl", $this->UserLvl);
        $this->connector->AddParameter("?user_name", $this->UserName);
        $this->connector->AddParameter("?user_email", $this->UserEmail);
        $this->connector->AddParameter("?fphoto", $this->Fphoto);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$rs = null;
        $rs1 = null;
		$this->connector->CommandText = 'DELETE FROM sys_users WHERE user_uid = ?id';
		$this->connector->AddParameter("?id", $id);
		$rs1 = $this->connector->ExecuteNonQuery();
		if ($rs1) {
			$this->connector->CommandText = 'DELETE FROM sys_user_rights WHERE user_uid = ?id';
			$this->connector->AddParameter("?id", $id);
			$rs = $this->connector->ExecuteNonQuery();
		}
		return $rs1;
	}

	public function LoginRecord($uid) {
		$this->connector->CommandText =
'UPDATE sys_users SET
	status = ?status
	, login_time = ?login_time
	, login_from = ?login_from
	, session_id = ?session_id
WHERE user_uid = ?uid';
		$this->connector->AddParameter("?status", $this->Status);
		$this->connector->AddParameter("?login_time", $this->LoginTime);
		$this->connector->AddParameter("?login_from", $this->LoginFrom);
		$this->connector->AddParameter("?session_id", $this->SessionId);
		$this->connector->AddParameter("?uid", $uid);
		return $this->connector->ExecuteNonQuery();
	}

    public function LoginActivityWriter($lOutletId,$lUserId,$lStatus){;
        $sqx = "Insert Into sys_login_logs (outlet_id,user_id,log_time,from_ipad,browser_app,ref_info,login_status)";
        $sqx.= " Values (?outlet_id,?user_id,now(),?ipad,?browser,?ref,?lstatus)";
        $this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?outlet_id", $lOutletId);
		$this->connector->AddParameter("?user_id", $lUserId);
		$this->connector->AddParameter("?ipad", getenv('REMOTE_ADDR'));
		$this->connector->AddParameter("?browser", getenv('HTTP_USER_AGENT'));
		$this->connector->AddParameter("?ref", getenv('HTTP_REFERER'));
		$this->connector->AddParameter("?lstatus", $lStatus);
        return $this->connector->ExecuteNonQuery();
    }

	public function UserActivityWriter($outlet_id,$resource,$process,$doc_no,$status){
		$sqx = "Insert Into sys_user_activity (outlet_id,user_uid,log_time,resource,process,doc_no,status)";
		$sqx.= " Values (?outlet_id,?user_uid,now(),?res,?process,?doc_no,?status)";
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?outlet_id", $outlet_id);
		$this->connector->AddParameter("?user_uid", AclManager::GetInstance()->GetCurrentUser()->Id);
		$this->connector->AddParameter("?res", $resource);
		$this->connector->AddParameter("?process", $process);
		$this->connector->AddParameter("?doc_no", $doc_no);
		$this->connector->AddParameter("?status", $status);
		return $this->connector->ExecuteNonQuery();
	}

	public function GetSysUserActivity($userUid,$stDate,$enDate){
		$sql = "Select a.* From vw_sys_user_activity a Where a.user_uid = ?userUid and a.log_time BETWEEN ?stDate and ?enDate Order By a.log_time;";
		$this->connector->CommandText = $sql;
		$this->connector->AddParameter("?userUid", $userUid);
		$this->connector->AddParameter("?stDate", date('Y-m-d',$stDate).' 00:00:00');
		$this->connector->AddParameter("?enDate", date('Y-m-d',$enDate).' 23:59:59');
		$rs = $this->connector->ExecuteQuery();
		return $rs;
	}

	public function GetUserLevel($getLevel = 5,$operator = '<'){
		$sql = "SELECT a.* FROM `sys_status_code` a WHERE a.`key` = 'user_level' AND a.`code` $operator $getLevel Order By a.urutan;";
		$this->connector->CommandText = $sql;
		$rs = $this->connector->ExecuteQuery();
		return $rs;
	}

    public function GetJsonUsers($userLvl = 0,$outletId = 0,$uLevel = 0,$userId = 0,$orderBy = "a.kd_outlet,a.user_name") {
        $url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,if(a.is_aktif = 1, 'Aktif','Non-Aktif') as user_status";
        $sqx.= ", if(not isnull(a.fphoto),concat('<img src=\"','".$url."',a.fphoto,'\" style=\"height:100px; width:120px\">'),'No Picture') as user_pics";
        $sqx.= " FROM vw_sys_users a, (SELECT @rownum := 0) b Where a.user_uid > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ".$outletId;
        }
        if ($uLevel > 0){
            if ($uLevel < 5){
                $sqx.= " And a.user_lvl < ".$uLevel." Or a.user_uid = ".$userId;
            }
        }
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $data = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $data[] = $row;
            }
        }
        $i = 0;
        if ($userLvl > 2) {
            foreach ($data as $key) {
                // add new button
                $data[$i]['button'] = '<button type="button" user_uid="' . $data[$i]['user_uid'] . '" user_name="' . $data[$i]['user_name'] . '" class="btn btn-primary btn-sm btuEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="button" user_uid="' . $data[$i]['user_uid'] . '" user_name="' . $data[$i]['user_name'] . '" class="btn btn-warning btn-sm btuDelete" ><i class="fa fa-remove"></i></button>';
                $i++;
            }
        }else{
            foreach ($data as $key) {
                // add new button
                $data[$i]['button'] = '<button type="button" user_uid="'.$data[$i]['user_uid'].'" user_name="'.$data[$i]['user_name'].'" class="btn btn-primary btn-sm btuEdit" ><i class="fa fa-edit"></i></button>';
                $i++;
            }
        }
        $datax = array('data' => $data);
        return $datax;
    }
}
