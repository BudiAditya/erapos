<?php

class StokInDetail extends EntityBase {
	public $Id;
	public $OutletId;
    public $StokInNo;
	public $Sku;
	public $Notes;
    public $QtyOrder = 0;
    public $QtyTerima = 0;
	public $Harga = 0;
    public $Diskon = 0;

	public function FillProperties(array $row) {
		$this->Id = $row["id"];
        $this->OutletId = $row["outlet_id"];
        $this->Sku = $row["sku"];
        $this->Notes = $row["notes"];
        $this->StokInNo = $row["stokin_no"];
        $this->QtyOrder = $row["qty_order"];
        $this->QtyTerima = $row["qty_terima"];
        $this->Harga = $row["harga"];
        $this->Diskon = $row["diskon"];
    }

	public function LoadById($id) {
		$this->connector->CommandText = "SELECT a.* FROM t_stokin_detail AS a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$this->FillProperties($rs->FetchAssoc());
		return $this;
	}

    public function FindById($id) {
        return $this->LoadById($id);
    }

    public function FindDuplicate($stokin_no,$sku) {
        $sql = "SELECT a.* FROM t_stokin_detail AS a WHERE a.sku = '".$sku."' And a.stokin_no = '".$stokin_no."'";
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }



	public function LoadByStokInNo($stokinNo, $orderBy = "a.sku") {
		$this->connector->CommandText = "SELECT a.* FROM t_stokin_detail AS a WHERE a.stokin_no = ?stokinNo ORDER BY $orderBy";
		$this->connector->AddParameter("?stokinNo", $stokinNo);
		$result = array();
		$rs = $this->connector->ExecuteQuery();
		if ($rs) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new StokInDetail();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

    public function Insert() {
		$this->connector->CommandText = "INSERT INTO t_stokin_detail(outlet_id,sku, stokin_no, qty_order, qty_terima, diskon, harga, notes) VALUES(?outlet_id,?sku,?stokin_no,?qty_order,?qty_terima,?diskon,?harga,?notes)";
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?stokin_no", $this->StokInNo);
        $this->connector->AddParameter("?qty_order", $this->QtyOrder);
        $this->connector->AddParameter("?qty_terima", $this->QtyTerima);
		$this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
		$rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Update($id) {
        $this->connector->CommandText = "UPDATE t_stokin_detail SET outlet_id = ?outlet_id, sku = ?sku, stokin_no = ?stokin_no, qty_order = ?qty_order, qty_terima = ?qty_terima, harga = ?harga, diskon = ?diskon, notes = ?notes WHERE id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?stokin_no", $this->StokInNo);
        $this->connector->AddParameter("?qty_order", $this->QtyOrder);
        $this->connector->AddParameter("?qty_terima", $this->QtyTerima);
        $this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Delete($id) {
        $this->connector->CommandText = "DELETE FROM t_stokin_detail WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

    public function GetJsonStokInDetail($stokinNo,$orderBy = "a.id") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,Format(a.harga,0) as fharga,Format((a.qty_terima * a.harga),0) as sub_total,Upper(c.nama) as nama,c.satuan";
        $sqx.= " FROM t_stokin_detail a Join m_produk c On a.sku = c.sku And a.outlet_id = c.outlet_id, (SELECT @rownum := 0) b Where a.stokin_no = '".$stokinNo."'";
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
            $data[$i]['button'] = '<button type="button" id_detail="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-xs btdRemove" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }
}
// End of File: estimasi_detail.php
