<?php

require_once("stokin_detail.php");

class StokIn extends EntityBase {
	private $editableDocId = array(1, 2, 3, 4);

	public static $StokInStatusCodes = array(
		0 => "OPEN",
		1 => "CLOSE",
        2 => "PAID",
		3 => "VOID"
	);
    
	public $Id;
    public $OutletId;
    public $OutletKode;
    public $OutletName;
    public $StokInNo;
    public $StokInDate;
    public $SuppCode;
    public $SuppName;
    public $SubTotal = 0;
    public $StokInStatus = 0;
    public $DStokInStatus;
    public $ExPoNo;
	public $Notes;
	public $PayAmt;
	public $CreatebyId;
	public $CreateTime;
	public $UpdatebyId;
	public $UpdateTime;

	/** @var StokInDetail[] */
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
        $this->StokInNo = $row["stokin_no"];
        $this->StokInDate = $row["stokin_date"];
        $this->SuppCode = $row["supp_code"];
        $this->SuppName = $row["supp_name"];
        $this->SubTotal = $row["sub_total"];
        $this->PayAmt = $row["pay_amt"];
        $this->ExPoNo = $row["ex_po_no"];
        $this->StokInStatus = $row["stokin_status"];
        $this->DStokInStatus = $row["dstokin_status"];
        $this->Notes = $row["notes"];
	}

	public function FormatStokInDate($format = HUMAN_DATE) {
		return is_int($this->StokInDate) ? date($format, $this->StokInDate) : date($format, strtotime(date('Y-m-d')));
	}

	/**
	 * @return StokInDetail[]
	 */
	public function LoadDetails() {
		if ($this->StokInNo == null) {
			return $this->Details;
		}
		$detail = new StokInDetail();
		$this->Details = $detail->LoadByStokInNo($this->StokInNo);
		return $this->Details;
	}

	/**
	 * @param int $id
	 * @return StokIn
	 */
	public function LoadById($id) {
	    $sqx = "SELECT a.* From vw_t_stokin a WHERE a.id = ?id";
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

	public function FindByStokInNo($stokinNo) {
        $sqx = "SELECT a.* From vw_t_stokin a Where a.stokin_no = ?stokinNo";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?stokinNo", $stokinNo);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function LoadByOutletId($outletId,$startDate = null, $endDate = null,$includeVoid = false) {
        $sqx = "SELECT a.* From vw_t_stokin a Where a.id > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if (!$includeVoid){
            $sqx.= " And a.stokin_status < 3";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new StokIn();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadOutstandingStokIn($outletId,$suppCode = null) {
        $sqx = "SELECT a.* From vw_t_stokin a Where a.sub_total - a.pay_amt > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($suppCode != null){
            $sqx.= " And a.supp_code = ?suppCode";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $this->connector->AddParameter("?suppCode", $suppCode);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new StokIn();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadPaidStokIn($outletId,$suppCode = null) {
        $sqx = "SELECT a.* From vw_t_stokin a Where a.sub_total - a.pay_amt <= 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($suppCode != null){
            $sqx.= " And a.supp_code = ?suppCode";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $this->connector->AddParameter("?suppCode", $suppCode);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new StokIn();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function Insert() {
        $sql = "INSERT INTO t_stokin (ex_po_no,notes,outlet_id,stokin_no,stokin_date,supp_code,stokin_status, createby_id, create_time)";
        $sql.= "VALUES(?ex_po_no,?notes,?outlet_id,?stokin_no,?stokin_date,?supp_code,?stokin_status, ?createby_id, now())";
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?stokin_no", $this->StokInNo);
        $this->connector->AddParameter("?stokin_date", date('Y-m-d',$this->StokInDate));
        $this->connector->AddParameter("?supp_code", $this->SuppCode);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?ex_po_no", $this->ExPoNo);
        $this->connector->AddParameter("?stokin_status", $this->StokInStatus);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->connector->CommandText = "SELECT LAST_INSERT_ID();";
			$this->Id = (int)$this->connector->ExecuteScalar();
		}
		return $rs;
	}

	public function Update($id) {
		$this->connector->CommandText = "UPDATE t_stokin a SET a.pay_amt = ?pay_amt, a.ex_po_no = ?ex_po_no, a.notes = ?notes, a.outlet_id = ?outlet_id, a.stokin_no = ?stokin_no, a.stokin_date = ?stokin_date, a.supp_code = ?supp_code, a.stokin_status = ?stokin_status, a.updateby_id = ?updateby_id, a.update_time = NOW() WHERE a.id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?stokin_no", $this->StokInNo);
        $this->connector->AddParameter("?stokin_date", $this->StokInDate);
        $this->connector->AddParameter("?supp_code", $this->SuppCode);;
        $this->connector->AddParameter("?stokin_status", $this->StokInStatus);
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
        $this->connector->CommandText = "SELECT fcProcessStokInApprove(".$id.",".$uid.") As valresult;";
        return $this->connector->ExecuteNonQuery();
    }

	public function Delete($id) {
        //baru hapus invoicenya
		$this->connector->CommandText = "Delete a From t_stokin as a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function Void($id) {
	    $this->connector->CommandText = "Update t_stokin a Set a.stokin_status = 3 WHERE a.id = ?id";
        $this->connector->AddParameter("?id", $id);
        return $this->connector->ExecuteNonQuery();
    }

    public function AutoStokInNo($outletId = 0, $stiDate = null){
	    $stiDate = strtotime($stiDate);
        $zrxNo = null;
        if ($outletId > 9) {
            $stokinNo = right(date('Y', $stiDate), 2) . 'G'.$outletId;
        }else{
            $stokinNo = right(date('Y', $stiDate), 2) . 'G0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.stokin_no),'".$stokinNo."') As stokinNo From t_stokin a Where year(a.stokin_date) = ".date('Y',$stiDate)." And a.outlet_id = ".$outletId;
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        $zrxNo = $row["stokinNo"];
        if ($stokinNo == $zrxNo){
            $stokinNo.= "00001";
        }else {
            $stokinNo = $stokinNo . str_pad(intval(right($zrxNo, 5)) + 1, 5, '0', STR_PAD_LEFT);
        }
        return $stokinNo;
    }

    public function GetJsonStokIns($outletId = 0,$includeVoid = false, $orderBy = "a.stokin_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, format(a.sub_total,0) as fsub_total, format(a.pay_amt,0) as fpay_amt, format(a.sub_total - a.pay_amt,0) as foutstanding FROM vw_t_stokin a, (SELECT @rownum := 0) b Where a.id > 0";
        //if ($outletId > 0) {
            $sqx .= " And a.outlet_id = $outletId";
        //}
        if (!$includeVoid){
            $sqx .= " And a.stokin_status < 3";
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
            $data[$i]['button'] = '<button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetJsonSalePusat($outletId = 0,$includeVoid = false, $orderBy = "a.stokin_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, format(a.sub_total,0) as fsub_total, format(a.pay_amt,0) as fpay_amt, format(a.sub_total - a.pay_amt,0) as foutstanding FROM vw_t_stokin a, (SELECT @rownum := 0) b Where a.id > 0";
        //if ($outletId > 0) {
        $sqx .= " And a.outlet_id > 0";
        //}
        if (!$includeVoid){
            $sqx .= " And a.stokin_status < 3";
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
            $data[$i]['button'] = '<button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_po="'.$data[$i]['id'].'" stokin_no="'.$data[$i]['stokin_no'].'" stokin_status="'.$data[$i]['stokin_status'].'" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetCountPendingStokIn($outletId = 0){
        $sql = "Select count(*) as pStiCnt From t_stokin a Where a.stokin_status = 0";
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
