<?php
class Kategori extends EntityBase {
	public $Id;
	public $IsDeleted = 0;
    public $EntityId = 0;
	public $Kategori;
	public $CreatebyId;
	public $UpdatebyId;

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
		$this->Kategori = $row["kategori"];
		$this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Kategori[]
	 */
	public function LoadAll($entityId = 0,$orderBy = "a.kategori", $includeDeleted = false) {
	    $sqx = $sqx = "SELECT a.* FROM m_kategori AS a Where a.id > 0";
	    if ($includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        if ($entityId > 0){
	        $sqx.= " And a.entity_id = ".$entityId;
        }
        $sqx.= " Order By $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Kategori();
				$temp->FillProperties($row);

				$result[] = $temp;
			}
		}
		return $result;
	}


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM m_kategori AS a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$row = $rs->FetchAssoc();
		$this->FillProperties($row);
		return $this;
	}

	/**
	 * @param int $id
	 * @return Kategori
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO m_kategori (entity_id,kategori,createby_id,create_time) VALUES(?entity_id,?kategori,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?entity_id", $this->EntityId);
        $this->connector->AddParameter("?kategori", $this->Kategori);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        $sql = 'UPDATE m_kategori SET entity_id = ?entity_id, kategori = ?kategori, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        $this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?entity_id", $this->EntityId);
        $this->connector->AddParameter("?kategori", $this->Kategori);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "UPDATE m_kategori SET is_deleted = 1 WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function GetJsonKategori($entityId = 0,$orderBy = "a.kategori", $includeDeleted = false) {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.* FROM m_kategori a, (SELECT @rownum := 0) b Where a.is_deleted = 0";
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
            $data[$i]['button'] = '<button type="submit" id_kategori="'.$data[$i]['id'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="submit" id_kategori="'.$data[$i]['id'].'" kategori="'.$data[$i]['kategori'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }
}
