<?php

class SalePusatDetail extends EntityBase {
	public $Id;
	public $OutletId = 1;
    public $TrxNo;
	public $Sku;
	public $Notes;
    public $QtyOrder = 0;
    public $QtyKirim = 0;
	public $Harga = 0;
    public $Diskon = 0;

	public function FillProperties(array $row) {
		$this->Id = $row["id"];        
		$this->OutletId = $row["outlet_id"];
        $this->Sku = $row["sku"];
        $this->Notes = $row["notes"];
        $this->TrxNo = $row["trx_no"];
        $this->QtyOrder = $row["qty_order"];
        $this->QtyKirim = $row["qty_kirim"];
        $this->Harga = $row["harga"];
        $this->Diskon = $row["diskon"];
    }

	public function LoadById($id) {
		$this->connector->CommandText = "SELECT a.* FROM t_salepusat_detail AS a WHERE a.id = ?id";
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

    public function FindDuplicate($trx_no,$sku) {
        $sql = "SELECT a.* FROM t_salepusat_detail AS a WHERE a.sku = '".$sku."' And a.trx_no = '".$trx_no."'";
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }



	public function LoadByTrxNo($trxNo, $orderBy = "a.sku") {
		$this->connector->CommandText = "SELECT a.* FROM t_salepusat_detail AS a WHERE a.trx_no = ?trxNo ORDER BY $orderBy";
		$this->connector->AddParameter("?trxNo", $trxNo);
		$result = array();
		$rs = $this->connector->ExecuteQuery();
		if ($rs) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new SalePusatDetail();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

    public function Insert() {
		$this->connector->CommandText = "INSERT INTO t_salepusat_detail(outlet_id, sku, trx_no, qty_order, qty_kirim, diskon, harga, notes) VALUES(?outlet_id,?sku,?trx_no,?qty_order,?qty_kirim,?diskon,?harga,?notes)";
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?qty_order", $this->QtyOrder);
        $this->connector->AddParameter("?qty_kirim", $this->QtyKirim);
		$this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
		$rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Update($id) {
        $this->connector->CommandText = "UPDATE t_salepusat_detail SET sku = ?sku, trx_no = ?trx_no, qty_order = ?qty_order, qty_kirim = ?qty_kirim, harga = ?harga, diskon = ?diskon, notes = ?notes WHERE id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?qty_order", $this->QtyOrder);
        $this->connector->AddParameter("?qty_kirim", $this->QtyKirim);
        $this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Delete($id) {
        $this->connector->CommandText = "DELETE FROM t_salepusat_detail WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

    public function GetJsonSalePusatDetail($trxNo,$orderBy = "a.id") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,Format(a.harga,0) as fharga,Format((a.qty_kirim * a.harga),0) as sub_total,Upper(c.nama) as nama,c.satuan";
        $sqx.= " FROM t_salepusat_detail a Join m_produk c On a.sku = c.sku, (SELECT @rownum := 0) b Where a.trx_no = '".$trxNo."'";
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
