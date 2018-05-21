<?php

require_once("sale_detail.php");

class Sale extends EntityBase {
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
    public $OutletAlamat;
    public $TrxNo;
    public $TrxTime;
    public $CustCode;
    public $CustName;
    public $DiscPct = 0;
    public $DiscAmt = 0;
    public $SubTotal = 0;
    public $MoneyAmt = 0;
    public $PayAmt = 0;
    public $IsTaxable = 0;
    public $TaxPct = 10;
    public $TaxAmt = 0;
	public $TrxStatus = 0;
    public $DTrxStatus;
	public $Notes;
	public $TableNo = 1;
	public $CreatebyId;
	public $CreateTime;
	public $UpdatebyId;
	public $UpdateTime;

	/** @var SaleDetail[] */
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
        $this->OutletAlamat = $row["alamat"];
        $this->TrxNo = $row["trx_no"];
        $this->TrxTime = $row["trx_time"];
        $this->CustCode = $row["cust_code"];
        $this->CustName = $row["cust_name"];
        $this->DiscPct = $row["disc_pct"];
        $this->DiscAmt = $row["disc_amt"];
        $this->SubTotal = $row["sub_total"];
        $this->IsTaxable = $row["is_taxable"];
        $this->TaxPct = $row["tax_pct"];
        $this->TaxAmt = $row["tax_amt"];
        $this->MoneyAmt = $row["money_amt"];
        $this->PayAmt = $row["pay_amt"];
        $this->TrxStatus = $row["trx_status"];
        $this->DTrxStatus = $row["dtrx_status"];
        $this->Notes = $row["notes"];
        $this->TableNo = $row["table_no"];
        //$this->CreatebyId = $row["createby_id"];
        //$this->CreateTime = $row["create_time"];
        //$this->UpdatebyId = $row["updateby_id"];
        //$this->UpdateTime = $row["update_time"];
	}

	public function FormatTrxTime($format = HUMAN_DATE) {
		return is_int($this->TrxTime) ? date($format, $this->TrxTime) : date($format, strtotime(date('Y-m-d H:i:s')));
	}

	/**
	 * @return SaleDetail[]
	 */
	public function LoadDetails() {
		if ($this->TrxNo == null) {
			return $this->Details;
		}
		$detail = new SaleDetail();
		$this->Details = $detail->LoadByTrxNo($this->TrxNo);
		return $this->Details;
	}

	/**
	 * @param int $id
	 * @return Sale
	 */
	public function LoadById($id) {
	    $sqx = "SELECT a.* From vw_t_sale a WHERE a.id = ?id";
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
        $sqx = "SELECT a.* From vw_t_sale a Where a.trx_no = ?trxNo";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?trxNo", $trxNo);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function LoadByOutletId($outletId,$startDate = null, $endDate = null) {
        $sqx = "SELECT a.* From vw_t_sale a Where a.trx_status < 3";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_time,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_time,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_time,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $sqx.= " Order By a.trx_time,a.trx_no,a.outlet_kode";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Sale();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function Insert() {
        $sql = "INSERT INTO t_sale (sub_total,disc_pct,disc_amt,is_taxable,tax_pct,tax_amt,table_no,notes,outlet_id,trx_no,trx_time,cust_code,money_amt,pay_amt,trx_status,createby_id,create_time)";
        $sql.= "VALUES(?sub_total,?disc_pct,?disc_amt,?is_taxable,?tax_pct,?tax_amt,?table_no,?notes,?outlet_id,?trx_no,?trx_time,?cust_code,?money_amt,?pay_amt,?trx_status, ?createby_id, now())";
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_time", $this->TrxTime);
        $this->connector->AddParameter("?cust_code", $this->CustCode);
        $this->connector->AddParameter("?money_amt", $this->MoneyAmt);
        $this->connector->AddParameter("?pay_amt", $this->PayAmt);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?table_no", $this->TableNo);
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?sub_total", $this->SubTotal);
        $this->connector->AddParameter("?disc_pct", $this->DiscPct);
        $this->connector->AddParameter("?disc_amt", $this->DiscAmt);
        $this->connector->AddParameter("?is_taxable", $this->IsTaxable);
        $this->connector->AddParameter("?tax_pct", $this->TaxPct);
        $this->connector->AddParameter("?tax_amt", $this->TaxAmt);
        $this->connector->AddParameter("?createby_id", $this->CreatebyId);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->connector->CommandText = "SELECT LAST_INSERT_ID();";
			$this->Id = (int)$this->connector->ExecuteScalar();
		}
		return $rs;
	}

	public function Update($id) {
		$this->connector->CommandText = "UPDATE t_sale a 
SET table_no = ?table_no
, a.notes = ?notes
, a.outlet_id = ?outlet_id
, a.trx_no = ?trx_no
, a.trx_time = ?trx_time
, a.cust_code = ?cust_code
, a.money_amt = ?money_amt
, a.pay_amt = ?pay_amt
, a.trx_status = ?trx_status
, a.updateby_id = ?updateby_id
, a.update_time = NOW()
, a.sub_total = ?sub_total
, a.disc_pct = ?disc_pct
, a.disc_amt = ?disc_amt
, a.is_taxable = ?is_taxable
, a.tax_pct = ?tax_pct
, a.tax_amt = ?tax_amt
WHERE a.id = ?id";
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?trx_no", $this->TrxNo);
        $this->connector->AddParameter("?trx_time", $this->TrxTime);
        $this->connector->AddParameter("?cust_code", $this->CustCode);
        $this->connector->AddParameter("?money_amt", $this->MoneyAmt);
        $this->connector->AddParameter("?pay_amt", $this->PayAmt);
        $this->connector->AddParameter("?trx_status", $this->TrxStatus);
        $this->connector->AddParameter("?notes", $this->Notes);
        $this->connector->AddParameter("?table_no", $this->TableNo);
        $this->connector->AddParameter("?sub_total", $this->SubTotal);
        $this->connector->AddParameter("?disc_pct", $this->DiscPct);
        $this->connector->AddParameter("?disc_amt", $this->DiscAmt);
        $this->connector->AddParameter("?is_taxable", $this->IsTaxable);
        $this->connector->AddParameter("?tax_pct", $this->TaxPct);
        $this->connector->AddParameter("?tax_amt", $this->TaxAmt);
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1){
            //proses stock inventory
            if ($this->TrxStatus == 1) {
                $this->connector->CommandText = "SELECT fcProcessInventorySaleTrx('".$this->TrxNo."') As valresult;";
                $rsx = $this->connector->ExecuteQuery();
            }
        }
        return $rs;
	}

	public function Delete($id) {
        //baru hapus invoicenya
		$this->connector->CommandText = "Delete a From t_sale as a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function Void($id) {
	    $this->FindById($id);
        //proses pembatalan keluar barang
        $this->connector->CommandText = "SELECT fcCancelProcessInventorySaleTrx('".$this->TrxNo."') As valresult;";
        $rsx = $this->connector->ExecuteQuery();
        //baru void invoicenya
        $this->connector->CommandText = "Update t_sale a Set a.trx_status = 3 WHERE a.id = ?id";
        $this->connector->AddParameter("?id", $id);
        return $this->connector->ExecuteNonQuery();
    }

    public function AutoTrxNo($outletId = 0, $trxDate = null){
	    $trxDate = strtotime($trxDate);
        $zrxNo = null;
        if ($outletId > 9) {
            $trxNo = right(date('Y', $trxDate), 2) . 'S'.$outletId;
        }else{
            $trxNo = right(date('Y', $trxDate), 2) . 'S0'.$outletId;
        }
        $sqx = "Select coalesce(max(a.trx_no),'".$trxNo."') As trxNo From t_sale a Where year(a.trx_time) = ".date('Y',$trxDate)." And a.outlet_id = ".$outletId;
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

    public function GetJsonSales($userLvl = 0,$outletId = 0,$trxDate = null,$includeVoid = false, $orderBy = "a.trx_no") {
        //$url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,format(a.pay_amt,0) as fsub_total, a.money_amt - a.sub_total as change_amt FROM vw_t_sale a, (SELECT @rownum := 0) b Where a.id > 0";
        if ($outletId > 0) {
            $sqx.= " And a.outlet_id = $outletId";
        }
        if (!$includeVoid){
            $sqx.= " And a.trx_status < 3";
        }
        if ($trxDate != null){
            $sqx.= " And DATE_FORMAT(a.trx_time, '%Y-%m-%d') = '".$trxDate."'";
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
        if ($userLvl > 2) {
            foreach ($data as $key) {
                // add new button
                $data[$i]['button'] = '<button type="button" id_sale="' . $data[$i]['id'] . '" trx_no="' . $data[$i]['trx_no'] . '" trx_status="' . $data[$i]['trx_status'] . '" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_sale="' . $data[$i]['id'] . '" trx_no="' . $data[$i]['trx_no'] . '" trx_status="' . $data[$i]['trx_status'] . '" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>
							       <button type="button" id_sale="' . $data[$i]['id'] . '" trx_no="' . $data[$i]['trx_no'] . '" trx_status="' . $data[$i]['trx_status'] . '" class="btn btn-warning btn-sm btsDelete" ><i class="fa fa-remove"></i></button>';
                $i++;
            }
        }else{
            foreach ($data as $key) {
                // add new button
                $data[$i]['button'] = '<button type="button" id_sale="' . $data[$i]['id'] . '" trx_no="' . $data[$i]['trx_no'] . '" trx_status="' . $data[$i]['trx_status'] . '" class="btn btn-primary btn-sm btsView" ><i class="fa fa-file-text-o"></i></button> 
                                   <button type="button" id_sale="' . $data[$i]['id'] . '" trx_no="' . $data[$i]['trx_no'] . '" trx_status="' . $data[$i]['trx_status'] . '" class="btn btn-primary btn-sm btsEdit" ><i class="fa fa-edit"></i></button>';
                $i++;
            }
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetDailySaleAverageGlobal($year = 0,$month = 0){
        $sql = "Select coalesce(avg(a.sale_per_date),0) as avg_sale From vw_t_sale_all_sum_per_date a Where a.sale_per_date > 0";
        if ($year > 0){
            $sql.= " And year(a.trx_date) = ".$year;
        }
        if ($month > 0){
            $sql.= "  And Month(a.trx_date) = ".$month;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["avg_sale"]);
    }

    public function GetDailySaleAverageOutlet($outletId = 0,$year = 0,$month = 0){
        $sql = "Select coalesce(avg(a.sale_per_date),0) as avg_sale From vw_t_sale_outlet_sum_per_date a Where a.outlet_id = ".$outletId;
        if ($year > 0){
            $sql.= " And year(a.trx_date) = ".$year;
        }
        if ($month > 0){
            $sql.= "  And Month(a.trx_date) = ".$month;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["avg_sale"]);
    }

    public function GetSalesSummaryGlobal($year = 0,$month = 0){
        $sql = "Select coalesce(sum(a.sale_per_date),0) as sum_sale From vw_t_sale_all_sum_per_date a Where a.sale_per_date > 0";
        if ($year > 0){
            $sql.= " And year(a.trx_date) = ".$year;
        }
        if ($month > 0){
            $sql.= "  And Month(a.trx_date) = ".$month;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["sum_sale"]);
    }

    public function GetSalesSummaryOutlet($outletId = 0,$year = 0,$month = 0){
        $sql = "Select coalesce(sum(a.sale_per_date),0) as sum_sale From vw_t_sale_outlet_sum_per_date a Where a.outlet_id = ".$outletId;
        if ($year > 0){
            $sql.= " And year(a.trx_date) = ".$year;
        }
        if ($month > 0){
            $sql.= "  And Month(a.trx_date) = ".$month;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["sum_sale"]);
    }

    public function GetSalesSummaryToDay($outletId = 0,$trxDate = null){
        $sql = "Select coalesce(sum(a.sale_per_date),0) as sum_sale From vw_t_sale_outlet_sum_per_date a Where a.sale_per_date > 0";
        if ($outletId > 0){
            $sql.= " And a.outlet_id = ".$outletId;
        }
        if ($trxDate != null){
            $sql.= "  And a.trx_date = '".$trxDate."'";
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["sum_sale"]);
    }

    public function GetSalesPercentage(){
        $sql = "Select a.outlet_id, d.kode as outlet_kode, a.sum_sale, round(a.sum_sale * 100/b.tsale,0) as sale_percent
From vw_t_sale_sumby_outlet a Join m_outlet d On a.outlet_id = d.id
Cross Join (Select sum(c.sum_sale) as tsale From vw_t_sale_sumby_outlet as c) as b Order By a.sum_sale Desc;";
        $this->connector->CommandText = $sql;
        return $this->connector->ExecuteQuery();
    }

    public function GetTop5SaleByQty(){
        $sql = "Select a.* From vw_t_sale_sku_summary a Order By a.sum_qty Desc Limit 5";
        $this->connector->CommandText = $sql;
        return $this->connector->ExecuteQuery();
    }

    public function GetTop5SaleByAmount(){
        $sql = "Select a.* From vw_t_sale_sku_summary a Order By a.sum_amount Desc Limit 5";
        $this->connector->CommandText = $sql;
        return $this->connector->ExecuteQuery();
    }

    public function GetCountPendingSale($outletId = 0){
        $sql = "Select count(*) as pSaleCnt From t_sale a Where a.trx_status = 0";
        if ($outletId > 0){
            $sql.= " And a.outlet_id = ".$outletId;
        }
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["pSaleCnt"]);
    }

    public function Load4RekapDate($outletId = 0, $startDate = null, $endDate = null) {
        $sqx = "Select a.outlet_id, b.kode AS outlet_kode, b.outlet_name, a.trx_date, a.sale_per_date as jumlah,a.sub_total,a.diskon,a.pajak From vw_t_sale_outlet_sum_per_date AS a JOIN m_outlet AS b On a.outlet_id = b.id Where a.sale_per_date > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $sqx.= " Order By b.kode, a.trx_date";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        return $this->connector->ExecuteQuery();
    }

    public function Load4RekapItem($outletId = 0, $startDate = null, $endDate = null) {
        $sqx = "Select a.sku, a.nama, a.satuan, sum(a.qty) as sum_qty, sum(a.jumlah) as sum_jumlah From vw_t_sale_detail AS a Where a.qty > 0";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ?outletId";
        }
        if ($startDate != null && $endDate == null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') >= '".date('Y-m-d',$startDate)."'";
        }elseif ($startDate == null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') <= '".date('Y-m-d',$endDate)."'";
        }elseif ($startDate != null && $endDate != null){
            $sqx.= " And Date_Format(a.trx_date,'%Y-%m-%d') Between '".date('Y-m-d',$startDate)."' And '".date('Y-m-d',$endDate)."'";
        }
        $sqx.= " Group By a.sku, a.nama, a.satuan";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?outletId", $outletId);
        return $this->connector->ExecuteQuery();
    }
}


// End of File: estimasi.php
