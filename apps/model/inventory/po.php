<?php

require_once("po_detail.php");

class Po extends EntityBase {
	private $editableDocId = array(1, 2, 3, 4);

	public static $PoStatusCodes = array(
		0 => "OPEN",
		1 => "CLOSE",
        2 => "PAID",
		3 => "VOID"
	);
    
	public $Id;
    public $OutletId;
    public $OutletKode;
    public $OutletName;
    public $PoNo;
    public $PoDate;
    public $SuppCode;
    public $SuppName;
    public $SubTotal = 0;
    public $PoStatus = 0;
    public $DPoStatus;
	public $Notes;
	public $CreatebyId;
	public $CreateTime;
	public $UpdatebyId;
	public $UpdateTime;

	/** @var PoDetail[] */
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
        $this->PoNo = $row["po_no"];
        $this->PoDate = $row["po_date"];
        $this->SuppCode = $row["supp_code"];
        $this->SuppName = $row["supp_name"];
        $this->SubTotal = $row["sub_total"];
        $this->PoStatus = $row["po_status"];
        $this->DPoStatus = $row["dpo_status"];
        $this->Notes = $row["notes"];
	}

	public function FormatPoDate($format = HUMAN_DATE) {
		return is_int($this->PoDate) ? date($format, $this->PoDate) : date($format, strtotime(date('Y-m-d')));
	}

	/**
	 * @return PoDetail[]
	 */
	public function LoadDetails() {
		if ($this->PoNo == null) {
			return $this->Details;
		}
		$detail = new PoDetail();
		$this->Details = $detail->LoadByPoNo($this->PoNo);
		return $this->Details;
	}

	/**
	 * @param int $id
	 * @return Po
	 */
	public function LoadById($id) {
	    $sqx = "SELECT a.* From vw_t_po a WHERE a.id = ?id";
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

	public function FindByPoNo($poNo) {
        $sqx = "SELECT a.* From vw_t_po a Where a.po_no = ?poNo";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?poNo", $poNo);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function LoadByOutletId($outletId,$includeVoid = false,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_po a Where a.id > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if (!$includeVoid){
            $sqx.= " And a.po_status < 3";
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Po();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function Insert() {
        $sql = "INSERT INTO t_po (notes,outlet_id,po_no,po_date,supp_code,po_status, createby_id, create_time)";
        $sql.= "VALUES(?notes,?outlet_id,?po_no,?po_date,?supp_code,?po_status, ?createby_id, now())";
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?po_no", $this->PoNo);
        $this->connector->AddParameter("?po_date", date('Y-m-d',$this->PoDate));
        $this->connector->AddParameter("?supp_code", $this->SuppCode);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?po_status", $this->PoStatus);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->connector->CommandText = "SELECT LAST_INSERT_ID();";
			$this->Id = (int)$this->connector->ExecuteScalar();
		}
		return $rs;
	}

	public function Update($id) {
		$this->connector->CommandText = "UPDATE t_po a SET a.notes = ?notes, a.outlet_id = ?outlet_id, a.po_no = ?po_no, a.po_date = ?po_date, a.supp_code = ?supp_code, a.po_status = ?po_status, a.updateby_id = ?updateby_id, a.update_time = NOW() WHERE a.id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?po_no", $this->PoNo);
        $this->connector->AddParameter("?po_date", $this->PoDate);
        $this->connector->AddParameter("?supp_code", $this->SuppCode);;
        $this->connector->AddParameter("?po_status", $this->PoStatus);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteNonQuery();
		return $rs;
	}

	public function Delete($id) {
        //baru hapus invoicenya
		$this->connector->CommandText = "Delete a From t_po as a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function Proses($id,$stino,$sptno,$tgl,$uid) {
        //baru hapus invoicenya
        $this->connector->CommandText = "SELECT fcProcessPoApprove(".$id.",'".$stino."','".$sptno."','".$tgl."',".$uid.") As valresult;";
        return $this->connector->ExecuteNonQuery();
    }

    public function Void($id) {
	    $this->connector->CommandText = "Update t_po a Set a.po_status = 3 WHERE a.id = ?id";
        $this->connector->AddParameter("?id", $id);
        return $this->connector->ExecuteNonQuery();
    }

    public function AutoPoNo($outletId = 0, $poDate = null){
	    //$poDate = strtotime($poDate);
        $zrxNo = null;
        if ($outletId > 9) {
            $poNo = right(date('Y', $poDate), 2) . 'O'.$outletId;
        }else{
            $poNo = right(date('Y', $poDate), 2) . 'O0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.po_no),'".$poNo."') As poNo From t_po a Where year(a.po_date) = ".date('Y',$poDate)." And a.outlet_id = ".$outletId;
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        $zrxNo = $row["poNo"];
        if ($poNo == $zrxNo){
            $poNo.= "00001";
        }else {
            $poNo = $poNo . str_pad(intval(right($zrxNo, 5)) + 1, 5, '0', STR_PAD_LEFT);
        }
        return $poNo;
    }

    public function GetJsonPos($outletId = 0,$includeVoid = false, $orderBy = "a.po_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*, format(a.sub_total,0) as fsub_total FROM vw_t_po a, (SELECT @rownum := 0) b Where a.id > 0";
        if ($outletId > 0) {
            $sqx .= " And a.outlet_id = $outletId";
        }
        if (!$includeVoid){
            $sqx .= " And a.po_status < 3";
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
            $data[$i]['button'] = '<button type="button" id_po="'.$data[$i]['id'].'" po_no="'.$data[$i]['po_no'].'" po_status="'.$data[$i]['po_status'].'" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_po="'.$data[$i]['id'].'" po_no="'.$data[$i]['po_no'].'" po_status="'.$data[$i]['po_status'].'" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_po="'.$data[$i]['id'].'" po_no="'.$data[$i]['po_no'].'" po_status="'.$data[$i]['po_status'].'" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetCountPendingPo($outletId = 0){
        $sql = "Select count(*) as pPoCnt From t_po a Where a.po_status = 0";
        if ($outletId > 0){
            $sql.= " And a.outlet_id = ".$outletId;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["pPoCnt"]);
    }
}


// End of File: estimasi.php
