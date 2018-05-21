<?php

require_once("salepusat_detail.php");

class SalePusat extends EntityBase {
	private $editableDocId = array(1, 2, 3, 4);

	public static $TrxStatusCodes = array(
		0 => "OPEN",
		1 => "CLOSE",
        2 => "PAID",
		3 => "VOID"
	);
    
	public $Id;
    public $OutletId;
    public $OutletKode;
    public $OutletName;
    public $TrxNo;
    public $TrxDate;
    public $CustCode;
    public $CustName;
    public $SubTotal = 0;
    public $TrxStatus = 0;
    public $DTrxStatus;
    public $ExPoNo;
    public $ByOutletId;
    public $ByOutletKode;
    public $ByOutletName;
	public $Notes;
	public $PayAmt;
	public $CreatebyId;
	public $CreateTime;
	public $UpdatebyId;
	public $UpdateTime;

	/** @var SalePusatDetail[] */
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
        $this->TrxNo = $row["trx_no"];
        $this->TrxDate = $row["trx_date"];
        $this->CustCode = $row["cust_code"];
        $this->CustName = $row["cust_name"];
        $this->SubTotal = $row["sub_total"];
        $this->PayAmt = $row["pay_amt"];
        $this->ExPoNo = $row["ex_po_no"];
        $this->TrxStatus = $row["trx_status"];
        $this->DTrxStatus = $row["dtrx_status"];
        $this->Notes = $row["notes"];
        $this->ByOutletId = $row["by_outlet_id"];
        $this->ByOutletKode = $row["by_outlet_kode"];
        $this->ByOutletName = $row["by_outlet_name"];
	}

	public function FormatTrxDate($format = HUMAN_DATE) {
		return is_int($this->TrxDate) ? date($format, $this->TrxDate) : date($format, strtotime(date('Y-m-d')));
	}

	/**
	 * @return SalePusatDetail[]
	 */
	public function LoadDetails() {
		if ($this->TrxNo == null) {
			return $this->Details;
		}
		$detail = new SalePusatDetail();
		$this->Details = $detail->LoadByTrxNo($this->TrxNo);
		return $this->Details;
	}

	/**
	 * @param int $id
	 * @return SalePusat
	 */
	public function LoadById($id) {
	    $sqx = "SELECT a.* From vw_t_salepusat a WHERE a.id = ?id";
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

	public function FindByTrxNo($trxNo) {
        $sqx = "SELECT a.* From vw_t_salepusat a Where a.trx_no = ?trxNo";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?trxNo", $trxNo);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function Load4Report($byOutletKode,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_salepusat a Where a.trx_status < 3";
        if (trim($byOutletKode) != '0'){
            $sqx.= " And a.by_outlet_kode = '".$byOutletKode."'";
        }
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new SalePusat();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadOutstandingSalePusat($outletId,$custCode = null) {
        $sqx = "SELECT a.* From vw_t_salepusat a Where a.sub_total - a.pay_amt > 0 And a.outlet_id = ?outletId";
        if ($custCode != null){
            $sqx.= " And a.by_outlet_kode = ?custCode";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $this->connector->AddParameter("?custCode", $custCode);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new SalePusat();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadPaidSalePusat($outletId,$custCode = null) {
        $sqx = "SELECT a.* From vw_t_salepusat a Where a.sub_total - a.pay_amt <= 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($custCode != null){
            $sqx.= " And a.cust_code = ?custCode";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $this->connector->AddParameter("?custCode", $custCode);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new SalePusat();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function Insert() {
        $sql = "INSERT INTO t_salepusat (pay_amt, by_outlet_id, ex_po_no,notes,outlet_id,trx_no,trx_date,cust_code,trx_status, createby_id, create_time)";
        $sql.= "VALUES(?pay_amt,?by_outlet_id,?ex_po_no,?notes,?outlet_id,?trx_no,?trx_date,?cust_code,?trx_status, ?createby_id, now())";
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_date", date('Y-m-d',$this->TrxDate));
        $this->connector->AddParameter("?cust_code", $this->CustCode);
        $this->connector->AddParameter("?by_outlet_id", $this->ByOutletId);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?pay_amt", $this->PayAmt);
        $this->connector->AddParameter("?ex_po_no", $this->ExPoNo);
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->connector->CommandText = "SELECT LAST_INSERT_ID();";
			$this->Id = (int)$this->connector->ExecuteScalar();
		}
		return $rs;
	}

	public function Update($id) {
		$this->connector->CommandText = "UPDATE t_salepusat a SET a.by_outlet_id = ?by_outlet_id, a.pay_amt = ?pay_amt, a.ex_po_no = ?ex_po_no, a.notes = ?notes, a.outlet_id = ?outlet_id, a.trx_no = ?trx_no, a.trx_date = ?trx_date, a.cust_code = ?cust_code, a.trx_status = ?trx_status, a.updateby_id = ?updateby_id, a.update_time = NOW() WHERE a.id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?by_outlet_id", $this->ByOutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_date", $this->TrxDate);
        $this->connector->AddParameter("?cust_code", $this->CustCode);;
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?pay_amt", $this->PayAmt);
        $this->connector->AddParameter("?ex_po_no", $this->ExPoNo);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteNonQuery();
		return $rs;
	}

    public function Proses($id,$uid) {
        //baru hapus invoicenya
        $this->connector->CommandText = "SELECT fcProcessSalePusatApprove(".$id.",".$uid.") As valresult;";
        return $this->connector->ExecuteNonQuery();
    }

	public function Delete($id) {
        //baru hapus invoicenya
		$this->connector->CommandText = "Delete a From t_salepusat as a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function Void($id) {
	    $this->connector->CommandText = "Update t_salepusat a Set a.trx_status = 3 WHERE a.id = ?id";
        $this->connector->AddParameter("?id", $id);
        return $this->connector->ExecuteNonQuery();
    }

    public function AutoTrxNo($outletId = 0, $sptDate = null){
	    $sptDate = strtotime($sptDate);
        $zrxNo = null;
        if ($outletId > 9) {
            $trxNo = right(date('Y', $sptDate), 2) . 'T'.$outletId;
        }else{
            $trxNo = right(date('Y', $sptDate), 2) . 'T0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.trx_no),'".$trxNo."') As trxNo From t_salepusat a Where year(a.trx_date) = ".date('Y',$sptDate)." And a.outlet_id = ".$outletId;
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        $zrxNo = $row["trxNo"];
        if ($trxNo == $zrxNo){
            $trxNo.= "00001";
        }else {
            $trxNo = $trxNo . str_pad(intval(right($zrxNo, 5)) + 1, 5, '0', STR_PAD_LEFT);
        }
        return $trxNo;
    }

    public function GetJsonSalePusat($outletId = 0,$includeVoid = false, $orderBy = "a.trx_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, format(a.sub_total,0) as fsub_total, format(a.pay_amt,0) as fpay_amt, format(a.sub_total - a.pay_amt,0) as foutstanding FROM vw_t_salepusat a, (SELECT @rownum := 0) b Where a.id > 0";
        //if ($outletId > 0) {
        $sqx .= " And a.outlet_id = $outletId";
        //}
        if (!$includeVoid){
            $sqx .= " And a.trx_status < 3";
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
            $data[$i]['button'] = '<button type="button" id_po="'.$data[$i]['id'].'" trx_no="'.$data[$i]['trx_no'].'" trx_status="'.$data[$i]['trx_status'].'" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_po="'.$data[$i]['id'].'" trx_no="'.$data[$i]['trx_no'].'" trx_status="'.$data[$i]['trx_status'].'" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_po="'.$data[$i]['id'].'" trx_no="'.$data[$i]['trx_no'].'" trx_status="'.$data[$i]['trx_status'].'" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetCountPendingSalePusat($outletId = 0){
        $sql = "Select count(*) as pStiCnt From t_salepusat a Where a.trx_status = 0";
        if ($outletId > 0){
            $sql.= " And a.outlet_id = ".$outletId;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["pStiCnt"]);
    }
}


// End of File: estimasi.php
