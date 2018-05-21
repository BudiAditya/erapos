<?php
class Supplier extends EntityBase {
	public $Id;
	public $IsDeleted = 0;
    public $OutletId = 0;
	public $Kode;
	public $Nama;
    public $Alamat;
    public $Kota;
    public $Phone;
    public $Email;
    public $CreditTerms = 0;
    public $CreditLimit = 0;
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
		$this->IsDeleted = $row["is_deleted"] == 1;
		$this->OutletId = $row["outlet_id"];
		$this->Kode = $row["kode"];
		$this->Nama = $row["nama"];
        $this->Alamat = $row["alamat"];
        $this->Kota = $row["kota"];
        $this->Phone = $row["phone"];
        $this->CreditTerms = $row["credit_terms"];
        $this->CreditLimit = $row["credit_limit"];
        $this->CreatebyId = $row["createby_id"];
		$this->UpdatebyId = $row["updateby_id"];
		$this->Email = $row["email"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return supplier[]
	 */
	public function LoadAll($entityId = 0,$outletId = 0,$orderBy = "a.kode", $includeDeleted = false) {
	    $sqx = $sqx = "SELECT a.* FROM m_supplier AS a Left Join m_outlet b On a.outlet_id = b.id Where a.id > 0";
	    if ($includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        if ($entityId > 0){
	        $sqx.= " And b.entity_id = ".$entityId;
        }
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ".$outletId;
        }
        $sqx.= " Order By $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Supplier();
				$temp->FillProperties($row);

				$result[] = $temp;
			}
		}
		return $result;
	}


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM m_supplier AS a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
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
	 * @return supplier
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO m_supplier (phone,email,outlet_id,kode,nama,alamat,credit_terms,credit_limit,kota,createby_id,create_time)';
	    $sqx.= ' VALUES(?phone,?email,?outlet_id,?kode,?nama,?alamat,?credit_terms,?credit_limit,?kota,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?email", $this->Email);
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?kode", $this->Kode);
        $this->connector->AddParameter("?nama", $this->Nama);
        $this->connector->AddParameter("?alamat", $this->Alamat);
        $this->connector->AddParameter("?credit_terms", $this->CreditTerms);
        $this->connector->AddParameter("?credit_limit", $this->CreditLimit);
        $this->connector->AddParameter("?kota", $this->Kota);
        $this->connector->AddParameter("?phone", $this->Phone, "char");
		$this->connector->AddParameter("?createby_id", $this->CreatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Update($id) {
        $sql = 'UPDATE m_supplier SET phone = ?phone, email = ?email, outlet_id = ?outlet_id, nama = ?nama, alamat = ?alamat, credit_terms = ?credit_terms, credit_limit = ?credit_limit, kota = ?kota, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        $this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?email", $this->Email);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?nama", $this->Nama);
        $this->connector->AddParameter("?alamat", $this->Alamat);
        $this->connector->AddParameter("?credit_terms", $this->CreditTerms);
        $this->connector->AddParameter("?credit_limit", $this->CreditLimit);
        $this->connector->AddParameter("?kota", $this->Kota);
        $this->connector->AddParameter("?phone", $this->Phone, "char");
        $this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "UPDATE m_supplier SET is_deleted = 1 WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function GetJsonsupplier($entityId = 0,$orderBy = "a.kode", $includeDeleted = false) {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,c.kode as outlet_kode,c.outlet_name FROM m_supplier a Left Join m_outlet c On a.outlet_id = c.id, (SELECT @rownum := 0) b Where a.is_deleted = 0";
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
            $data[$i]['button'] = '<button type="submit" id_customer="'.$data[$i]['id'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="submit" id_customer="'.$data[$i]['id'].'" nama="'.$data[$i]['nama'].'" kode="'.$data[$i]['kode'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetsupplierCode($outletId = 0, $rtype = 1) {
        // function untuk menggenerate kode contact
        $xcode = null;
        $ckode = null;
        $relcd = null;
        $nol = "0000";
        if ($outletId > 9 && $outletId < 100){
            $ckode = "SP".$outletId;
        }else{
            $ckode = "SP0".$outletId;
        }
        $ins = $ckode;
        $this->connector->CommandText = "SELECT a.kode FROM m_supplier a WHERE a.outlet_id = $outletId And LEFT(a.kode,4) = ?ins ORDER BY a.kode DESC LIMIT 1";
        $this->connector->AddParameter("?ins", $ins);
        $rs = $this->connector->ExecuteQuery();
        if ($rs != null) {
            $row = $rs->FetchAssoc();
            $relcd = $row["kode"];
            if ($relcd == "") {
                return $xcode = $ins . "0001";
            } else {
                $num = substr($relcd, 6, 4);
                $num = $num + 1;
                return $xcode = $ins . substr($nol, 0, 4 - strlen($num)) . $num;
            }
        } else {
            return $xcode;
        }
    }
}
