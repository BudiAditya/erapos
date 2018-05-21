<?php

class ProduksiDetail extends EntityBase {
	public $Id;
	public $OutletId = 1;
    public $ProdNo;
	public $Sku;
	public $ProdType;
    public $Qty = 0;
	public $Harga = 0;

	public function FillProperties(array $row) {
		$this->Id = $row["id"];
        $this->OutletId = $row["outlet_id"];
        $this->Sku = $row["sku"];
        $this->ProdType = $row["prod_type"];
        $this->ProdNo = $row["prod_no"];
        $this->Qty = $row["qty"];
        $this->Harga = $row["harga"];
    }

	public function LoadById($id) {
		$this->connector->CommandText = "SELECT a.* FROM t_produksi_detail AS a WHERE a.id = ?id";
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

    public function FindDuplicate($outletId,$prodNo,$prodType,$sku) {
        $sql = "SELECT a.* FROM t_produksi_detail AS a WHERE a.outlet_id = $outletId And a.sku = '".$sku."' And a.prod_type = '".$prodType."' And a.prod_no = '".$prodNo."'";
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }



	public function LoadByProdNo($prodNo, $prodType = 0, $orderBy = "a.sku") {
	    $sqx = "SELECT a.* FROM t_produksi_detail AS a WHERE a.prod_no = ?poNo";
	    if ($prodType > 0){
	        $sqx.= " And a.prod_type = ?prodType";
        }
	    $sqx.= " ORDER BY $orderBy";
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?prodType", $prodType);
        $this->connector->AddParameter("?poNo", $prodNo);
		$result = array();
		$rs = $this->connector->ExecuteQuery();
		if ($rs) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new PoDetail();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

    public function Insert() {
		$this->connector->CommandText = "INSERT INTO t_produksi_detail(outlet_id,sku, prod_no, qty, harga, prod_type) VALUES(?outlet_id,?sku,?prod_no,?qty,?harga,?prod_type)";
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?prod_no", $this->ProdNo);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?prod_type", $this->ProdType);
		$rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Update($id) {
        $this->connector->CommandText = "UPDATE t_produksi_detail SET outlet_id = ?outlet_id, sku = ?sku, prod_no = ?prod_no, qty = ?qty, harga = ?harga, prod_type = ?prod_type WHERE id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?prod_no", $this->ProdNo);
        $this->connector->AddParameter("?qty", $this->Qty);
        $this->connector->AddParameter("?harga", $this->Harga);
        $this->connector->AddParameter("?prod_type", $this->ProdType);
        $this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

	public function Delete($id) {
        $this->connector->CommandText = "DELETE FROM t_produksi_detail WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
        $rs = $this->connector->ExecuteNonQuery();
        return $rs;
	}

    public function GetJsonProduksiDetail($prodNo,$prodType = 0,$orderBy = "a.prod_type,a.sku,c.nama") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,Format(a.harga,0) as fharga,Format((a.qty * a.harga),0) as sub_total,Upper(c.nama) as nama,c.satuan";
        $sqx.= " FROM t_produksi_detail a Join m_produk c On a.sku = c.sku And a.outlet_id = c.outlet_id, (SELECT @rownum := 0) b Where a.prod_no = '".$prodNo."'";
        if ($prodType > 0){
            $sqx.= " And a.prod_type = $prodType";
        }
        $sqx.= " Order By $orderBy";
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
