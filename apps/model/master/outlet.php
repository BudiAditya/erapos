<?php
class Outlet extends EntityBase {
	public $Id;
	public $IsDeleted = false;
    public $EntityId;
	public $Kode;
	public $OutletName;
    public $Alamat;
    public $Kota;
    public $Phone;
    public $Kordinat;
    public $Pic;
    public $FLogo;
    public $CreatebyId;
	public $UpdatebyId;
	public $CabType = 0;
	public $CabStatus = 1;
	public $AllowMinus = 1;
	public $PrintMode;
	public $PrintName;

	public function __construct($id = null) {
		parent::__construct();
		if (is_numeric($id)) {
			$this->FindById($id);
		}
	}

	public function FillProperties(array $row) {
		$this->Id = $row["id"];
		$this->IsDeleted = $row["is_deleted"] == 1;
		$this->EntityId = $row["entity_id"];
		$this->Kode = $row["kode"];
		$this->OutletName = $row["outlet_name"];
        $this->Alamat = $row["alamat"];
        $this->Kota = $row["kota"];
        $this->Phone = $row["phone"];
        $this->Pic = $row["pic"];
        $this->FLogo = $row["flogo"];
        $this->PrintMode = $row["print_mode"];
		$this->PrintName = $row["print_name"];
		$this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
		$this->CabType = $row["cab_type"];
		$this->CabStatus = $row["cab_status"];
		$this->AllowMinus = $row["allow_minus"];
		$this->Kordinat = $row["kordinat"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Outlet[]
	 */
	public function LoadAll($entityId = 0,$orderBy = "a.kode", $includeDeleted = false) {
	    if ($includeDeleted){
            $sqx = "SELECT a.* FROM m_outlet AS a Where a.entity_id = $entityId ORDER BY $orderBy";
        }else{
            $sqx = "SELECT a.* FROM m_outlet AS a Where a.entity_id = $entityId And a.is_deleted = 0 Order By $orderBy";
        }
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Outlet();
				$temp->FillProperties($row);

				$result[] = $temp;
			}
		}
		return $result;
	}

    public function LoadById($outletId = 0,$orderBy = "a.kode", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_outlet AS a Where a.id = $outletId";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Outlet();
                $temp->FillProperties($row);

                $result[] = $temp;
            }
        }
        return $result;
    }


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM m_outlet AS a WHERE a.id = ?id";
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
	    $sqx = 'INSERT INTO m_outlet(phone,kordinat,allow_minus,cab_status,cab_type,entity_id,kode,outlet_name,alamat,pic,flogo,kota,print_mode,print_name,createby_id,create_time)';
	    $sqx.= ' VALUES(?phone,?kordinat,?allow_minus,?cab_status,?cab_type,?entity_id,?kode,?outlet_name,?alamat,?pic,?flogo,?kota,?print_mode,?print_name,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?kordinat", $this->Kordinat);
        $this->connector->AddParameter("?allow_minus", $this->AllowMinus);
		$this->connector->AddParameter("?cab_status", $this->CabStatus);
		$this->connector->AddParameter("?cab_type", $this->CabType);
		$this->connector->AddParameter("?entity_id", $this->EntityId);
        $this->connector->AddParameter("?kode", $this->Kode);
        $this->connector->AddParameter("?outlet_name", $this->OutletName);
        $this->connector->AddParameter("?alamat", $this->Alamat);
        $this->connector->AddParameter("?pic", $this->Pic);
        $this->connector->AddParameter("?flogo", $this->FLogo);
        $this->connector->AddParameter("?kota", $this->Kota);
        $this->connector->AddParameter("?phone", $this->Phone, "char");
		$this->connector->AddParameter("?print_mode", $this->PrintMode);
		$this->connector->AddParameter("?print_name", $this->PrintName);
		$this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        if ($this->FLogo == null){
            $sql = 'UPDATE m_outlet SET phone = ?phone, kordinat = ?kordinat, allow_minus = ?allow_minus, cab_status = ?cab_status, cab_type = ?cab_type, entity_id = ?entity_id, kode = ?kode, outlet_name = ?outlet_name,	alamat = ?alamat, pic = ?pic, kota = ?kota, print_mode = ?print_mode, print_name = ?print_name, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        }else{
            $sql = 'UPDATE m_outlet SET phone = ?phone, kordinat = ?kordinat, allow_minus = ?allow_minus, cab_status = ?cab_status, cab_type = ?cab_type, entity_id = ?entity_id,	kode = ?kode, outlet_name = ?outlet_name,	alamat = ?alamat, pic = ?pic, flogo = ?flogo, kota = ?kota, print_mode = ?print_mode, print_name = ?print_name, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        }
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?kordinat", $this->Kordinat);
        $this->connector->AddParameter("?allow_minus", $this->AllowMinus);
        $this->connector->AddParameter("?cab_status", $this->CabStatus);
        $this->connector->AddParameter("?cab_type", $this->CabType);
        $this->connector->AddParameter("?entity_id", $this->EntityId);
        $this->connector->AddParameter("?kode", $this->Kode);
        $this->connector->AddParameter("?outlet_name", $this->OutletName);
        $this->connector->AddParameter("?alamat", $this->Alamat);
        $this->connector->AddParameter("?pic", $this->Pic);
        $this->connector->AddParameter("?flogo", $this->FLogo);
        $this->connector->AddParameter("?kota", $this->Kota);
        $this->connector->AddParameter("?phone", $this->Phone, "char");
        $this->connector->AddParameter("?print_mode", $this->PrintMode);
        $this->connector->AddParameter("?print_name", $this->PrintName);
		$this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "UPDATE m_outlet SET is_deleted = 1 WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function GetJsonOutlet($entityId = 0,$orderBy = "a.kode", $includeDeleted = false) {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,if(a.cab_status = 1, 'Aktif','Non-Aktif') as outlet_status FROM m_outlet a, (SELECT @rownum := 0) b Where a.is_deleted = 0";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $data = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $data[] = $row;
            }
        }
        $i = 0;
        foreach ($data as $key) {
            // add new button
            $data[$i]['button'] = '<button type="submit" id_outlet="'.$data[$i]['id'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="submit" id_outlet="'.$data[$i]['id'].'" outlet_name="'.$data[$i]['outlet_name'].'" kode="'.$data[$i]['kode'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetOutletCount($entityId = 0, $activeStatus = 1){
        $sqx = "Select count(a.id) As cntOutlet From m_outlet a Where a.is_deleted = 0";
        if ($entityId > 0){
            $sqx.= " And a.entity_id = ".$entityId;
        }
        if ($activeStatus > -1){
            $sqx.= " And a.cab_status = ".$activeStatus;
        }
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["cntOutlet"]);
    }
}
