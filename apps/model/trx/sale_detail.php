<?php

class SaleDetail extends EntityBase {
	public $Id;
    public $TrxNo;
    public $OutletId;
	public $Sku;
	public $Notes;
    public $Qty = 0;
	public $Harga = 0;
    public $Diskon;
    public $ProdukName;

	public function FillProperties(array $row) {
		$this->Id = $row["id"];
        $this->OutletId = $row["outlet_id"];
        $this->Sku = $row["sku"];
        $this->Notes = $row["notes"];
        $this->TrxNo = $row["trx_no"];
        $this->Qty = $row["qty"];
        $this->Harga = $row["harga"];
        $this->Diskon = $row["diskon"];
        $this->ProdukName = $row["nama"];
    }

	public function LoadById($id) {
		$this->connector->CommandText = "SELECT a.*,b.nama FROM t_sale_detail AS a Join m_produk AS b On a.sku = b.sku WHERE a.id = ?id";
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

    public function FindDuplicate($trx_no,$sku,$oti) {
        $sql = "SELECT a.*,b.nama FROM t_sale_detail AS a Join m_produk AS b On a.sku = b.sku WHERE a.outlet_id = $oti And a.sku = '".$sku."' And a.trx_no = '".$trx_no."'";
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }



	public function LoadByTrxNo($trxNo, $orderBy = "a.sku") {
		$this->connector->CommandText = "SELECT a.*,b.nama FROM t_sale_detail AS a Join m_produk AS b On a.sku = b.sku And a.outlet_id = b.outlet_id WHERE a.trx_no = ?trxNo ORDER BY $orderBy";
		$this->connector->AddParameter("?trxNo", $trxNo);
		$result = array();
		$rs = $this->connector->ExecuteQuery();
		if ($rs) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new SaleDetail();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

    public function Insert() {
		$this->connector->CommandText = "INSERT INTO t_sale_detail(outlet_id,sku, trx_no, qty, diskon, harga, notes) VALUES(?outlet_id,?sku,?trx_no,?qty,?diskon,?harga,?notes)";
		$this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?qty", $this->Qty);
		$this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
		$rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Update($id) {
        $this->connector->CommandText = "UPDATE t_sale_detail SET outlet_id = ?outlet_id, sku = ?sku, trx_no = ?trx_no, qty = ?qty, harga = ?harga, diskon = ?diskon, notes = ?notes WHERE id = ?id";
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?diskon", $this->Diskon);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Delete($id) {
        $this->connector->CommandText = "DELETE FROM t_sale_detail WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

    public function GetJsonSaleDetail($trxNo,$orderBy = "a.id") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,Format(a.harga,0) as fharga,Format((a.qty * a.harga),0) as sub_total,Upper(c.nama) as nama,c.satuan";
        $sqx.= " FROM t_sale_detail a Join m_produk c On a.sku = c.sku And a.outlet_id = c.outlet_id, (SELECT @rownum := 0) b Where a.trx_no = '".$trxNo."'";
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
            $data[$i]['button'] = '<button type="button" id_detail="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-primary btn-xs btdAdd" ><i class="fa fa-plus"></i></button> 
							   <button type="button" id_detail="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-xs btdRemove" ><i class="fa fa-minus"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetSubTotal($trxNo){
        $this->connector->CommandText = "Select coalesce(sum(a.qty * a.harga),0) As SubTotal From t_sale_detail a Where a.trx_no = '".$trxNo."'";
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null) {
            return 0;
        }else{
            $row = $rs->FetchAssoc();
            return $row["SubTotal"];
        }
    }
}
// End of File: estimasi_detail.php
