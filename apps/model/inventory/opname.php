<?php
class Opname extends EntityBase {
	public $Id;
	public $OutletId = 0;
	public $Tanggal;
	public $OpType = 0;
	public $Sku;
	public $Qty;
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
		$this->Tanggal = $row["tanggal"];
        $this->OpType = $row["op_type"];
        $this->Sku = $row["sku"];
        $this->Qty = $row["qty"];
		$this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Tanggal[]
	 */
	public function LoadAll($outletId = 0,$orderBy = "a.tanggal") {
	    $sqx = $sqx = "SELECT a.* FROM t_stockopname AS a Where a.outlet_id = $outletId Order By $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Opname();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM t_stockopname AS a WHERE a.id = ?id";
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
	 * @return Tanggal
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO t_stockopname (outlet_id,tanggal,op_type,sku,qty,createby_id,create_time) VALUES(?outlet_id,?tanggal,?op_type,?sku,?qty,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?tanggal", $this->Tanggal);
        $this->connector->AddParameter("?op_type", $this->OpType);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
        if ($rs == 1) {
            $this->connector->CommandText = "SELECT LAST_INSERT_ID();";
            $this->Id = (int)$this->connector->ExecuteScalar();
            //proses stock inventory
            if ($this->Id > 0) {
                $this->connector->CommandText = "SELECT fcProcessOpname(".$this->Id.") As valresult;";
                $rsx = $this->connector->ExecuteQuery();
            }
        }
        return $rs;
	}

	public function Update($id) {
        $this->connector->CommandText = "SELECT fcCancelOpname(".$id.") As valresult;";
        $rsx = $this->connector->ExecuteQuery();
        $sql = 'UPDATE t_stockopname SET outlet_id = ?outlet_id, tanggal = ?tanggal, op_type = ?op_type, sku = ?sku, qty = ?qty, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        $this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?tanggal", $this->Tanggal);
        $this->connector->AddParameter("?op_type", $this->OpType);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
        $rs = $this->connector->ExecuteNonQuery();
        if ($rs == 1) {
            $this->connector->CommandText = "SELECT fcProcessOpname(".$id.") As valresult;";
            $rsx = $this->connector->ExecuteQuery();
        }
        return $rs;
	}

	public function Delete($id) {
        $this->connector->CommandText = "SELECT fcCancelOpname(".$id.") As valresult;";
        $rsx = $this->connector->ExecuteQuery();
		$this->connector->CommandText = "Delete a From t_stockopname AS a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function GetJsonOpname($outletId = 0,$orderBy = "a.tanggal") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, if(a.op_type = 1,'Stok Awal','Koreksi Stok') AS jns_opname,c.nama as nm_produk,c.satuan FROM t_stockopname a JOIN m_produk AS c On a.outlet_id = c.outlet_id And a.sku = c.sku, (SELECT @rownum := 0) b Where a.outlet_id = $outletId Order By ".$orderBy;
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
            $data[$i]['button'] = '<button type="submit" id_opname="'.$data[$i]['id'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							       <button type="submit" id_opname="'.$data[$i]['id'].'" tgl_opname="'.$data[$i]['tanggal'].'" sku="'.$data[$i]['sku'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

}
