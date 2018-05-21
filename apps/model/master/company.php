<?php
class Company extends EntityBase {
	public $IsDeleted = false;
	public $EntityId;
	public $EntityCd;
	public $Urutan;
	public $CompanyName;
	public $Address;
	public $City;
	public $Province;
	public $Telephone;
	public $Facsimile;
    public $Npwp;
    public $PersonInCharge;
    public $PicStatus;
    public $StartDate;
	public $Flogo;
	public $CreatebyId;
	public $UpdatebyId;

	public function __construct($id = null) {
		parent::__construct();
		if (is_numeric($id)) {
			$this->LoadById($id);
		}
	}

    public function FormatStartDate($format = HUMAN_DATE) {
        return is_int($this->StartDate) ? date($format, $this->StartDate) : null;
    }

	public function FillProperties(array $row) {
		//$this->IsDeleted = $row["is_deleted"] == 1;
		$this->EntityId = $row["entity_id"];
		$this->EntityCd = $row["entity_cd"];
		$this->Urutan = $row["urutan"];
		$this->CompanyName = $row["company_name"];
		$this->Address = $row["address"];
		$this->City = $row["city"];
		$this->Province = $row["province"];
		$this->Telephone = $row["telephone"];
		$this->Facsimile = $row["facsimile"];
        $this->Npwp = $row["npwp"];
        $this->PersonInCharge = $row["personincharge"];
        $this->PicStatus = $row["pic_status"];
        $this->StartDate = strtotime($row["start_date"]);
        $this->Flogo = $row["flogo"];
        $this->CreatebyId = $row["createby_id"];
        $this->UpdatebyId = $row["updateby_id"];
	}

    public function LoadAll($orderBy = "a.entity_cd", $includeDeleted = false) {
		if ($includeDeleted) {
			$this->connector->CommandText = "SELECT a.* FROM sys_company AS a ORDER BY $orderBy";
		} else {
			$this->connector->CommandText = "SELECT a.* FROM sys_company AS a ORDER BY $orderBy";
		}
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Company();
				$temp->FillProperties($row);

				$result[] = $temp;
			}
		}

		return $result;
	}

    public function LoadById($id) {
		$this->connector->CommandText = "SELECT a.* FROM sys_company AS a WHERE a.entity_id = ?id";
		$this->connector->AddParameter("?id", $id);

		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}

		$this->FillProperties($rs->FetchAssoc());
		return $this;
	}

	public function LoadByCode($code) {
		$this->connector->CommandText = "SELECT a.* FROM sys_company AS a WHERE a.entity_cd = ?code ORDER BY a.urutan";
		$this->connector->AddParameter("?code", $code);

		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}

		$this->FillProperties($rs->FetchAssoc());
		return $this;
	}

	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM sys_company AS a WHERE a.entity_id = ?entity_id";
		$this->connector->AddParameter("?entity_id", $id);
		$rs = $this->connector->ExecuteQuery();

		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}

		$row = $rs->FetchAssoc();
		$this->FillProperties($row);
		return $this;
	}

	public function Insert() {
	    $sqx = 'INSERT INTO sys_company(createby_id,create_time,entity_cd,company_name,address,city,province,telephone,facsimile,npwp,personincharge,pic_status,start_date,ppn_in_acc_id,ppn_out_acc_id)
VALUES(?createby_id,now(),?entity_cd,?company_name,?address,?city,?province,?telephone,?facsimile,?npwp,?personincharge,?pic_status,?start_date,?ppn_in_acc_id,?ppn_out_acc_id)';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?entity_cd", $this->EntityCd);
        $this->connector->AddParameter("?company_name", $this->CompanyName);
        $this->connector->AddParameter("?address", $this->Address);
        $this->connector->AddParameter("?city", $this->City);
        $this->connector->AddParameter("?province", $this->Province);
        $this->connector->AddParameter("?telephone", $this->Telephone);
        $this->connector->AddParameter("?facsimile", $this->Facsimile);
        $this->connector->AddParameter("?npwp", $this->Npwp);
        $this->connector->AddParameter("?personincharge", $this->PersonInCharge);
        $this->connector->AddParameter("?pic_status", $this->PicStatus);
        $this->connector->AddParameter("?start_date", date('Y-m-d',$this->StartDate));
		$this->connector->AddParameter("?ppn_in_acc_id", $this->PpnInAccId);
		$this->connector->AddParameter("?ppn_out_acc_id", $this->PpnOutAccId);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        $sqx = 'UPDATE sys_company SET
	entity_cd = ?entity_cd,
	company_name = ?company_name,
	address = ?address,
	city = ?city,
	province = ?province,
	telephone = ?telephone,
	facsimile = ?facsimile,
	npwp = ?npwp,
	personincharge = ?personincharge,
	pic_status = ?pic_status,
	ppn_in_acc_id = ?ppn_in_acc_id,
	ppn_out_acc_id = ?ppn_out_acc_id,
	start_date = ?start_date,
	updateby_id = ?updateby_id,
	update_time = now()';
	    if ($this->Flogo != null){
	        $sqx.= ', flogo = ?flogo';
        }
        $sqx.= ' WHERE entity_id = ?entity_id';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?entity_cd", $this->EntityCd);
        $this->connector->AddParameter("?company_name", $this->CompanyName);
        $this->connector->AddParameter("?address", $this->Address);
        $this->connector->AddParameter("?city", $this->City);
        $this->connector->AddParameter("?province", $this->Province);
        $this->connector->AddParameter("?telephone", $this->Telephone);
        $this->connector->AddParameter("?facsimile", $this->Facsimile);
        $this->connector->AddParameter("?npwp", $this->Npwp);
        $this->connector->AddParameter("?personincharge", $this->PersonInCharge);
        $this->connector->AddParameter("?pic_status", $this->PicStatus);
        $this->connector->AddParameter("?start_date", date('Y-m-d',$this->StartDate));
		$this->connector->AddParameter("?ppn_in_acc_id", $this->PpnInAccId);
		$this->connector->AddParameter("?ppn_out_acc_id", $this->PpnOutAccId);
		$this->connector->AddParameter("?entity_id", $id);
        $this->connector->AddParameter("?flogo", $this->Flogo);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
//		$this->connector->CommandText = 'DELETE FROM sys_company WHERE entity_id = ?id';
//		$this->connector->AddParameter("?id", $id);
		$this->connector->CommandText =
"UPDATE sys_company SET
	is_deleted = 1
WHERE entity_id = ?id";
		$this->connector->AddParameter("?id", $id);

		return $this->connector->ExecuteNonQuery();
	}

	public function GetJSonCompanies() {
		$sql = "SELECT a.entity_id,a.entity_cd,a.company_name FROM sys_company as a";
		$this->connector->CommandText = $sql;
		$data['count'] = $this->connector->ExecuteQuery()->GetNumRows();
		$sql.= " Order By a.urutan";
		$this->connector->CommandText = $sql;
		$rows = array();
		$rs = $this->connector->ExecuteQuery();
		while ($row = $rs->FetchAssoc()){
			$rows[] = $row;
		}
		$result = array('total'=>$data['count'],'rows'=>$rows);
		return $result;
	}

	public function GetComboJSonCompanies() {
		$sql = "SELECT a.entity_id,a.entity_cd,a.company_name FROM sys_company as a";
		$this->connector->CommandText = $sql;
		$sql.= " Order By a.urutan";
		$this->connector->CommandText = $sql;
		$rows = array();
		$rs = $this->connector->ExecuteQuery();
		while ($row = $rs->FetchAssoc()){
			$rows[] = $row;
		}
		$result = $rows;
		return $result;
	}

}

