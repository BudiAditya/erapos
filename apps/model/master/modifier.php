<?php
class Modifier extends EntityBase {
	public $Id;
	public $OutletId;
	public $SkuUtama;
    public $Sku;
    public $Display;
	public $Qty;
	public $Harga;
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
        $this->OutletId = $row["outlet_id"];
		$this->SkuUtama = $row["sku_utama"];
		$this->Sku = $row["sku"];
        $this->Display = $row["display"];
		$this->Qty = $row["qty"];
        $this->Harga = $row["harga"];
		$this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Modifier[]
	 */
	public function LoadAll($outletId = 0,$orderBy = "a.sku") {
	    $sqx = $sqx = "SELECT a.* FROM m_modifier AS a Order By $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Modifier();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM m_modifier AS a WHERE a.id = ?id";
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
	 * @return Modifier
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO m_modifier (outlet_id,display,sku_utama,sku,qty,harga,createby_id,create_time) VALUES(?outlet_id,?display,?sku_utama,?sku,?qty,?harga,?createby_id,now())';
		$this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
		$this->connector->AddParameter("?sku_utama", $this->SkuUtama);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?display", $this->Display);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        $sql = 'UPDATE m_modifier SET outlet_id = ?outlet_id, sku_utama = ?sku_utama, sku = ?sku, qty = ?qty, harga = ?harga, display = ?display, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        $this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku_utama", $this->SkuUtama);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?display", $this->Display);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "Delete a From m_modifier As a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function DelBySkuUtama($sku,$oti) {
        $this->connector->CommandText = "Delete a From m_modifier As a WHERE a.sku_utama = ?sku And a.outlet_id = $oti";
        $this->connector->AddParameter("?sku", $sku);
        return $this->connector->ExecuteNonQuery();
    }

    public function GetJsonModifer($sku = null,$oti = 0, $orderBy = "c.nama") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,c.nama,c.satuan";
        $sqx.= " FROM m_modifier a Join m_produk c On a.sku = c.sku And a.outlet_id = c.outlet_id, (SELECT @rownum := 0) b Where a.sku_utama = '".$sku."' And a.outlet_id = $oti";
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
            $data[$i]['button'] = '<button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-xs btmDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetJsonModifer1($sku = null,$oti = 0,$orderBy = "c.nama") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,c.nama,c.satuan";
        $sqx.= " FROM m_modifier a Join m_produk c On a.sku = c.sku And a.outlet_id = c.outlet_id, (SELECT @rownum := 0) b Where a.sku_utama = '".$sku."' And a.outlet_id = $oti";
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
            $data[$i]['button'] = '<button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-primary btn-xs btmEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-xs btmDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }
}
