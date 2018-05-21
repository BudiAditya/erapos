<?php

require_once("produksi_detail.php");

class Produksi extends EntityBase {
	private $editableDocId = array(1, 2, 3, 4);

	public static $ProdStatusCodes = array(
		0 => "OPEN",
		1 => "CLOSE",
        2 => "PAID",
		3 => "VOID"
	);
    
	public $Id;
    public $OutletId;
    public $OutletKode;
    public $OutletName;
    public $ProdNo;
    public $ProdDate;
    public $SubTotal = 0;
    public $ProdStatus = 0;
    public $DProdStatus;
	public $Notes;
	public $CreatebyId;
	public $CreateTime;
	public $UpdatebyId;
	public $UpdateTime;

	/** @var ProduksiDetail[] */
	public $Details = array();

	public function __construct($id = null) {
		parent::__construct();
		if (is_numeric($id)) {
			$this->LoadById($id);
		}
	}

	public function FillProperties(array $row) {
        $this->Id = $row["id"];
        $this->OutletId = $row["outlet_id"];
        $this->OutletKode = $row["outlet_kode"];
        $this->OutletName = $row["outlet_name"];
        $this->ProdNo = $row["prod_no"];
        $this->ProdDate = $row["prod_date"];
        $this->SubTotal = $row["sub_total"];
        $this->ProdStatus = $row["prod_status"];
        $this->DProdStatus = $row["dprod_status"];
        $this->Notes = $row["notes"];
	}

	public function FormatProdDate($format = HUMAN_DATE) {
		return is_int($this->ProdDate) ? date($format, $this->ProdDate) : date($format, strtotime(date('Y-m-d')));
	}

	/**
	 * @return ProduksiDetail[]
	 */
	public function LoadDetails() {
		if ($this->ProdNo == null) {
			return $this->Details;
		}
		$detail = new ProduksiDetail();
		$this->Details = $detail->LoadByProdNo($this->ProdNo);
		return $this->Details;
	}

	/**
	 * @param int $id
	 * @return Produksi
	 */
	public function LoadById($id) {
	    $sqx = "SELECT a.* From vw_t_produksi a WHERE a.id = ?id";
		$this->connector->CommandText = $sqx;
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

	public function FindByProdNo($prodNo) {
        $sqx = "SELECT a.* From vw_t_produksi a Where a.prod_no = ?prodNo";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?prodNo", $prodNo);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function LoadByOutletId($outletId,$includeVoid = false,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_produksi a Where a.id > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if (!$includeVoid){
            $sqx.= " And a.prod_status < 3";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produksi();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function Insert() {
        $sql = "INSERT INTO t_produksi (notes,outlet_id,prod_no,prod_date,prod_status, createby_id, create_time)";
        $sql.= "VALUES(?notes,?outlet_id,?prod_no,?prod_date,?prod_status, ?createby_id, now())";
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?prod_no", $this->ProdNo);
        $this->connector->AddParameter("?prod_date", date('Y-m-d',$this->ProdDate));
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?prod_status", $this->ProdStatus);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->connector->CommandText = "SELECT LAST_INSERT_ID();";
			$this->Id = (int)$this->connector->ExecuteScalar();
		}
		return $rs;
	}

	public function Update($id) {
		$this->connector->CommandText = "UPDATE t_produksi a SET a.notes = ?notes, a.outlet_id = ?outlet_id, a.prod_no = ?prod_no, a.prod_date = ?prod_date, a.prod_status = ?prod_status, a.updateby_id = ?updateby_id, a.update_time = NOW() WHERE a.id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?prod_no", $this->ProdNo);
        $this->connector->AddParameter("?prod_date", $this->ProdDate);
        $this->connector->AddParameter("?prod_status", $this->ProdStatus);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteNonQuery();
		return $rs;
	}

	public function Delete($id) {
        //baru hapus invoicenya
		$this->connector->CommandText = "Delete a From t_produksi as a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function Proses($prodNo) {
        //proses produksi
        $this->connector->CommandText = "SELECT fcProcessProduksi('".$prodNo."') As valresult;";
        return $this->connector->ExecuteNonQuery();
    }

    public function Void($prodNo) {
        //proses produksi
        $this->connector->CommandText = "SELECT fcCancelProduksi('".$prodNo."') As valresult;";
        return $this->connector->ExecuteNonQuery();
    }

    public function AutoProdNo($outletId = 0, $prodDate = null){
	    //$prodDate = strtotime($prodDate);
        $zrxNo = null;
        if ($outletId > 9) {
            $prodNo = right(date('Y', $prodDate), 2) . 'A'.$outletId;
        }else{
            $prodNo = right(date('Y', $prodDate), 2) . 'A0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.prod_no),'".$prodNo."') As prodNo From t_produksi a Where year(a.prod_date) = ".date('Y',$prodDate)." And a.outlet_id = ".$outletId;
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        $zrxNo = $row["prodNo"];
        if ($prodNo == $zrxNo){
            $prodNo.= "00001";
        }else {
            $prodNo = $prodNo . str_pad(intval(right($zrxNo, 5)) + 1, 5, '0', STR_PAD_LEFT);
        }
        return $prodNo;
    }

    public function GetJsonProduksis($outletId = 0,$includeVoid = false, $orderBy = "a.prod_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, format(a.sub_total,0) as fsub_total FROM vw_t_produksi a, (SELECT @rownum := 0) b Where a.id > 0";
        if ($outletId > 0) {
            $sqx .= " And a.outlet_id = $outletId";
        }
        if (!$includeVoid){
            $sqx .= " And a.prod_status < 3";
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
            $data[$i]['button'] = '<button type="button" id_prod="'.$data[$i]['id'].'" prod_no="'.$data[$i]['prod_no'].'" prod_status="'.$data[$i]['prod_status'].'" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_prod="'.$data[$i]['id'].'" prod_no="'.$data[$i]['prod_no'].'" prod_status="'.$data[$i]['prod_status'].'" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_prod="'.$data[$i]['id'].'" prod_no="'.$data[$i]['prod_no'].'" prod_status="'.$data[$i]['prod_status'].'" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetCountPendingProduksi($outletId = 0){
        $sql = "Select count(*) as pProduksiCnt From t_produksi a Where a.prod_status = 0";
        if ($outletId > 0){
            $sql.= " And a.outlet_id = ".$outletId;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["pProduksiCnt"]);
    }
}


// End of File: estimasi.php
