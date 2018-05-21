<?php
class Produk extends EntityBase {
	public $Id;
	public $IsDeleted = false;
    public $OutletId;
	public $KategoriId;
	public $Sku;
    public $Barcode;
    public $Nama;
    public $Satuan;
    public $HrgJual = 0;
    public $HrgBeli = 0;
    public $IsStock = 0;
    public $IsForsale = 0;
    public $IsModifier = 0;
    public $IsResep = 0;
    public $IsShowAll = 0;
    public $FPhoto;
    public $IsAktif = 1;
    public $AvailableOutlet;
    public $CreatebyId;
	public $UpdatebyId;
	public $Keterangan;

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
		$this->KategoriId = $row["kategori_id"];
		$this->Sku = $row["sku"];
        $this->Barcode = $row["barcode"];
        $this->Nama = $row["nama"];
        $this->Satuan = $row["satuan"];
        $this->IsStock = $row["is_stock"];
        $this->IsForsale = $row["is_forsale"];
        $this->IsShowAll = $row["is_showall"];
		$this->FPhoto = $row["fphoto"];
		$this->IsModifier = $row["is_modifier"];
		$this->IsAktif = $row["is_aktif"];
		$this->IsResep = $row["is_resep"];
		$this->HrgBeli = $row["hrg_beli"];
        $this->HrgJual = $row["hrg_jual"];
		$this->AvailableOutlet = $row["available_outlet"];
        $this->CreatebyId = $row["createby_id"];
        $this->UpdatebyId = $row["updateby_id"];
        $this->Keterangan = $row["keterangan"];
	}

	/**
	 * @param string $orderBy
	 * @param bool $includeDeleted
	 * @return Produk[]
	 */
	public function LoadAll($outletId = 0,$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where a.id > 0 And a.outlet_id = $outletId";
	    if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
		$this->connector->CommandText = $sqx;
		$rs = $this->connector->ExecuteQuery();
		$result = array();
		if ($rs != null) {
			while ($row = $rs->FetchAssoc()) {
				$temp = new Produk();
				$temp->FillProperties($row);
				$result[] = $temp;
			}
		}
		return $result;
	}

    public function LoadProdukDijual($outletId = 0,$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where a.is_forsale = 1 And a.outlet_id = $outletId";
        if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produk();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadByKategoriId($outletId = 0,$kategoriId = 0,$operator = '=',$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where a.kategori_id $operator $kategoriId And a.outlet_id = $outletId";
        if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produk();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadProdukMentah($outletId = 0,$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where a.kategori_id = 1 And a.outlet_id = $outletId";
        if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produk();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadProdukBahan($outletId = 0,$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where a.kategori_id < 3 And a.outlet_id = $outletId";
        if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produk();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function LoadProdukModifier($outletId = 0,$orderBy = "a.nama", $includeDeleted = false) {
        $sqx = "SELECT a.* FROM m_produk AS a Where (a.kategori_id > 2 and a.kategori_id < 5) And a.outlet_id = $outletId";
        if (!$includeDeleted){
            $sqx.= " And a.is_deleted = 0";
        }
        $sqx.= " ORDER BY $orderBy;";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Produk();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }


	public function FindById($id) {
		$this->connector->CommandText = "SELECT a.* FROM m_produk AS a WHERE a.id = ?id";
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$row = $rs->FetchAssoc();
		$this->FillProperties($row);
		return $this;
	}

    public function FindBySku($sku,$outletId) {
        $this->connector->CommandText = "SELECT a.* FROM m_produk AS a WHERE a.sku = ?sku And a.outlet_id = $outletId";
        $this->connector->AddParameter("?sku", $sku);
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
	 * @return Produk
	 */
	public function LoadById($id) {
		return $this->FindById($id);
	}

	public function Insert() {
	    $sqx = 'INSERT INTO m_produk(hrg_beli,keterangan,available_outlet,satuan,hrg_jual,is_resep,is_aktif,is_modifier,outlet_id,kategori_id,sku,barcode,is_stock,is_forsale,nama,is_showall,fphoto,createby_id,create_time)';
	    $sqx.= ' VALUES(?hrg_beli,?keterangan,?available_outlet,?satuan,?hrg_jual,?is_resep,?is_aktif,?is_modifier,?outlet_id,?kategori_id,?sku,?barcode,?is_stock,?is_forsale,?nama,?is_showall,?fphoto,?createby_id,now())';
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?hrg_beli", $this->HrgBeli);
        $this->connector->AddParameter("?hrg_jual", $this->HrgJual);
        $this->connector->AddParameter("?is_resep", $this->IsResep);
		$this->connector->AddParameter("?is_aktif", $this->IsAktif);
		$this->connector->AddParameter("?is_modifier", $this->IsModifier);
		$this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?kategori_id", $this->KategoriId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?barcode", $this->Barcode);
        $this->connector->AddParameter("?is_stock", $this->IsStock);
        $this->connector->AddParameter("?is_forsale", $this->IsForsale);
        $this->connector->AddParameter("?nama", $this->Nama);
        $this->connector->AddParameter("?satuan", $this->Satuan, "char");
		$this->connector->AddParameter("?is_showall", $this->IsShowAll);
		$this->connector->AddParameter("?fphoto", $this->FPhoto);
        $this->connector->AddParameter("?available_outlet", $this->AvailableOutlet);
		$this->connector->AddParameter("?createby_id", $this->CreatebyId);
        $this->connector->AddParameter("?keterangan", $this->Keterangan);
		$rs = $this->connector->ExecuteNonQuery();
        if ($rs == 1) {
            $this->connector->CommandText = "SELECT LAST_INSERT_ID();";
            $this->Id = (int)$this->connector->ExecuteScalar();
        }
        return $rs;
	}

	public function Update($id) {
        if ($this->FPhoto == null){
            $sql = 'UPDATE m_produk SET hrg_beli = ?hrg_beli, keterangan = ?keterangan, available_outlet = ?available_outlet, satuan = ?satuan, hrg_jual = ?hrg_jual, is_resep = ?is_resep, is_aktif = ?is_aktif, is_modifier = ?is_modifier, outlet_id = ?outlet_id, kategori_id = ?kategori_id, sku = ?sku, barcode = ?barcode, is_stock = ?is_stock, is_forsale = ?is_forsale, nama = ?nama, is_showall = ?is_showall, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        }else{
            $sql = 'UPDATE m_produk SET hrg_beli = ?hrg_beli, keterangan = ?keterangan, available_outlet = ?available_outlet, satuan = ?satuan, hrg_jual = ?hrg_jual, is_resep = ?is_resep, is_aktif = ?is_aktif, is_modifier = ?is_modifier, outlet_id = ?outlet_id,	kategori_id = ?kategori_id, sku = ?sku,	barcode = ?barcode, is_stock = ?is_stock, is_forsale = ?is_forsale, nama = ?nama, is_showall = ?is_showall, fphoto = ?fphoto, updateby_id = ?updateby_id, update_time = now() WHERE id = ?id';
        }
		$this->connector->CommandText = $sql;
        $this->connector->AddParameter("?id", $this->Id);
        $this->connector->AddParameter("?hrg_beli", $this->HrgBeli);
        $this->connector->AddParameter("?hrg_jual", $this->HrgJual);
        $this->connector->AddParameter("?is_resep", $this->IsResep);
        $this->connector->AddParameter("?is_aktif", $this->IsAktif);
        $this->connector->AddParameter("?is_modifier", $this->IsModifier);
        $this->connector->AddParameter("?outlet_id", $this->OutletId);
        $this->connector->AddParameter("?kategori_id", $this->KategoriId);
        $this->connector->AddParameter("?sku", $this->Sku);
        $this->connector->AddParameter("?barcode", $this->Barcode);
        $this->connector->AddParameter("?is_stock", $this->IsStock);
        $this->connector->AddParameter("?is_forsale", $this->IsForsale);
        $this->connector->AddParameter("?nama", $this->Nama);
        $this->connector->AddParameter("?satuan", $this->Satuan, "char");
        $this->connector->AddParameter("?is_showall", $this->IsShowAll);
        $this->connector->AddParameter("?fphoto", $this->FPhoto);
        $this->connector->AddParameter("?available_outlet", $this->AvailableOutlet);
		$this->connector->AddParameter("?updateby_id", $this->UpdatebyId);
        $this->connector->AddParameter("?keterangan", $this->Keterangan);
		return $this->connector->ExecuteNonQuery();
	}

	public function Delete($id) {
		$this->connector->CommandText = "UPDATE m_produk SET is_deleted = 1 WHERE id = ?id";
		$this->connector->AddParameter("?id", $id);
		return $this->connector->ExecuteNonQuery();
	}

    public function GetJsonProduk($outletId = 0,$orderBy = "a.kategori_id,a.nama", $includeDeleted = false) {
        $url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,if(a.is_aktif = 1, 'Aktif','Non-Aktif') as produk_status,c.kategori";
        $sqx.= ",format(a.hrg_jual,0) as fhrgjual,format(a.hrg_beli,0) as fhrgbeli";
        $sqx.= ", if(not isnull(a.fphoto),concat('<img src=\"','".$url."',a.fphoto,'\" style=\"height:100px; width:120px\">'),'No Picture') as produk_pics";
        $sqx.= " FROM m_produk a Join m_kategori c On a.kategori_id = c.id, (SELECT @rownum := 0) b Where a.is_deleted = 0 And a.outlet_id = $outletId Order By ".$orderBy;
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
            $data[$i]['button'] = '<button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetJsonProdukDijual($outletId = 0,$orderBy = "a.kategori_id,a.nama", $includeDeleted = false) {
        $url = base_url("");
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,if(a.is_aktif = 1, 'Aktif','Non-Aktif') as produk_status,c.kategori";
        $sqx.= ",format(a.hrg_jual,0) as fhrgjual,format(a.hrg_beli,0) as fhrgbeli";
        $sqx.= ", if(not isnull(a.fphoto),concat('<img src=\"','".$url."',a.fphoto,'\" style=\"height:100px; width:120px\">'),'No Picture') as produk_pics";
        $sqx.= " FROM m_produk a Join m_kategori c On a.kategori_id = c.id, (SELECT @rownum := 0) b Where a.kategori_id > 2 And a.is_deleted = 0 And a.outlet_id = $outletId Order By ".$orderBy;
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
            $data[$i]['button'] = '<button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-primary btn-sm btEdit" ><i class="fa fa-edit"></i></button> 
							   <button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-warning btn-sm btDelete" ><i class="fa fa-remove"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetAutoSKU($kategoriId,$outletId) {
        // function untuk menggenerate kode sku
        $sku = $kategoriId."001";
        $sqx = "SELECT max(a.sku) AS pSku FROM m_produk a WHERE a.kategori_id = $kategoriId And a.outlet_id = $outletId";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        if ($rs != null) {
            $row = $rs->FetchAssoc();
            $psku = $row["pSku"];
            if (strlen($psku) == 4){
                $sku = $psku +1;
            }
            return $sku;
        } else {
            return $sku;
        }
    }

    public function GetJsonBahan($outletId = null,$orderBy = "a.kategori_id,a.nama") {
        $sqx = "SELECT @rownum := @rownum + 1 AS urutan,a.*,format(a.hrg_jual,0) as fharga FROM m_produk a, (SELECT @rownum := 0) b Where a.is_deleted = 0 And a.kategori_id = 2 And a.outlet_id = $outletId Order By ".$orderBy;
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
            $data[$i]['button'] = '<button type="button" id_produk="'.$data[$i]['id'].'" sku="'.$data[$i]['sku'].'" nama="'.$data[$i]['nama'].'" class="btn btn-default btn-xs btPilih" ><i class="fa fa-edit"></i></button>';
            $i++;
        }
        $datax = array('data' => $data);
        return $datax;
    }

    public function GetProdukSaleItem($outletId = 0){
        $sql = "Select count(*) as produkCount From m_produk a Where a.kategori_id > 2 And a.outlet_id = $outletId";
        $this->connector->CommandText = $sql;
        $rs = $this->connector->ExecuteQuery();
        $row = $rs->FetchAssoc();
        return strval($row["produkCount"]);
    }
}
