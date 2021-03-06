<?php
class Receive extends EntityBase {
	public $Id;
	public $OutletId = 0;
	public $OutletKode;
	public $OutletName;
	public $TrxNo;
	public $TrxDate;
	public $CustCode;
	public $ReffNo;
	public $Jumlah = 0;
	public $TrxStatus = 0;
	public $TrxMode = 1;
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
        $this->OutletKode = $row["outlet_kode"];
        $this->OutletName = $row["outlet_name"];
		$this->TrxNo = $row["trx_no"];
        $this->TrxDate = $row["trx_date"];
        $this->CustCode = $row["cust_code"];
        $this->Jumlah = $row["jumlah"];
        $this->TrxStatus = $row["trx_status"];
        $this->TrxMode = $row["trx_mode"];
        $this->ReffNo = $row["reff_no"];
		$this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Receive[]
	 */
	public function LoadAll($outletId = 0,$orderBy = "b.outlet_kode,a.trx_no") {
	    $sqx = $sqx = "SELECT a.*,b.kode as outlet_kode,b.outlet_name FROM t_receive AS a Join m_outlet AS b On a.outlet_id = b.id Where a.id > 0";
	    if ($outletId > 0){
	        $sqx.= " And a.outlet_id = ".$outletId;
        }
        $sqx.= " Order By $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Receive();
				$temp->FillProperties($row);

				$result[] = $temp;
			}
		}
		return $result;
	}


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.*,b.kode as outlet_kode,b.outlet_name FROM t_receive AS a Join m_outlet AS b On a.outlet_id = b.id Where a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$row = $rs->FetchAssoc();
		$this->FillProperties($row);
		return $this;
	}

    public function FindByReffNo($reffNo) {
        $this->connector->CommandText = "SELECT a.*,b.kode as outlet_kode,b.outlet_name FROM t_receive AS a Join m_outlet AS b On a.outlet_id = b.id Where a.reff_no = ?reffNo";
        $this->connector->AddParameter("?reffNo", $reffNo);
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
	 * @return Receive
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO t_receive (reff_no,outlet_id,trx_no,trx_date,cust_code,jumlah,trx_status,trx_mode,createby_id,create_time) VALUES(?reff_no,?outlet_id,?trx_no,?trx_date,?cust_code,?jumlah,?trx_status,?trx_mode,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?reff_no", $this->ReffNo);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_date", date('Y-m-d',$this->TrxDate));
        $this->connector->AddParameter("?cust_code", $this->CustCode);
        $this->connector->AddParameter("?jumlah", $this->Jumlah);
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?trx_mode", $this->TrxMode);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        $sql = 'UPDATE t_receive SET 
outlet_id = ?outlet_id, 
reff_no = ?reff_no,
trx_no = ?trx_no,
trx_date = ?trx_date,
cust_code = ?cust_code,
jumlah = ?jumlah,
trx_status = ?trx_status,
trx_mode = ?trx_mode,
updateby_id = ?updateby_id, 
update_time = now() 
WHERE id = ?id';
        $this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?reff_no", $this->ReffNo);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_date", date('Y-m-d',$this->TrxDate));
        $this->connector->AddParameter("?cust_code", $this->CustCode);
        $this->connector->AddParameter("?jumlah", $this->Jumlah);
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?trx_mode", $this->TrxMode);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "Delete From t_receive WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function AutoTrxNo($outletId = 0, $trxDate = null){
        $zrxNo = null;
        if ($outletId > 9) {
            $trxNo = right(date('Y', $trxDate), 2) . 'R'.$outletId;
        }else{
            $trxNo = right(date('Y', $trxDate), 2) . 'R0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.trx_no),'".$trxNo."') As trxNo From t_receive a Where year(a.trx_date) = ".date('Y',$trxDate)." And a.outlet_id = ".$outletId;
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

    public function GetJsonReceive($outletId = 0,$orderBy = "a.kategori", $includeDeleted = false) {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,format(a.jumlah,0) as fjumlah FROM vw_t_receive a, (SELECT @rownum := 0) b Where a.outlet_id = $outletId";
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
            $data[$i]['button'] = '<button type="submit" id_receive="'.$data[$i]['id'].'" trx_no="'.$data[$i]['trx_no'].'" trx_mode="'.$data[$i]['trx_mode'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function LoadByOutletId($outletId,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_receive a Where a.id > 0";
        //if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        //}
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $sqx.= " Order By a.trx_date,a.trx_no,a.outlet_kode";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Receive();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function Load4Report($custCode,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_receive a Where a.trx_status < 3";
        if ($custCode != ''){
            $sqx.= " And a.cust_code = ?custCode";
        }
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $sqx.= " Order By a.trx_date,a.trx_no,a.outlet_kode";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?custCode", $custCode);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Receive();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }
}
