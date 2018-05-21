<?php
class Card extends EntityBase {

    public $Id;
    public $OutletId;
    public $OutletKode;
    public $OutletNama;
    public $Sku;
    public $ProdukNama;
    public $ProdukSatuan;
    public $HrgBeli = 0;
    public $HrgJual = 0;
    public $QtyAwal = 0;
    public $QtyIn = 0;
	public $QtyOut = 0;
    public $QtyKoreksi = 0;
	public $QtyStok = 0;

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
        $this->OutletNama = $row["outlet_nama"];
        $this->Sku = $row["sku"];
        $this->ProdukNama = $row["produk_nama"];
        $this->ProdukSatuan = $row["produk_satuan"];
        $this->HrgBeli = $row["hrg_beli"];
        $this->HrgJual = $row["hrg_jual"];
        $this->QtyAwal = $row["qty_awal"];
        $this->QtyIn = $row["qty_in"];
        $this->QtyOut = $row["qty_out"];
        $this->QtyKoreksi = $row["qty_koreksi"];
        $this->QtyStok = $row["qty_stok"];
	}

    public function LoadByOutlet($outletId = 0,$orderBy = "a.sku") {
        $sqx = "SELECT a.* FROM vw_t_stock a WHERE a.outlet_id = $outletId";
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteQuery();
        $result = array();
        if ($rs != null) {
            while ($row = $rs->FetchAssoc()) {
                $temp = new Card();
                $temp->FillProperties($row);
                $result[] = $temp;
            }
        }
        return $result;
    }

	public function LoadById($id) {
	    $sqx = "SELECT a.* FROM vw_t_stock a WHERE a.id = ?id";
		$this->connector->CommandText = $sqx;
		$this->connector->AddParameter("?id", $id);
		$rs = $this->connector->ExecuteQuery();
		if ($rs == null || $rs->GetNumRows() == 0) {
			return null;
		}
		$this->FillProperties($rs->FetchAssoc());
		return $this;
	}

    public function LoadBySku($sku,$outletId = 0) {
        $sqx = "SELECT a.* FROM vw_t_stock a WHERE a.sku = ?sku";
        if ($outletId > 0){
            $sqx.= " And a.outlet_id = ".$outletId;
        }
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku);
        $rs = $this->connector->ExecuteQuery();
        if ($rs == null || $rs->GetNumRows() == 0) {
            return null;
        }
        $this->FillProperties($rs->FetchAssoc());
        return $this;
    }

    public function GetStockHistory($outletId,$sku,$startDate = null, $endDate = null){
        $sqx = null;
        // create card temp table
        $sqx = 'CREATE TEMPORARY TABLE `tmp_card` (
                `trx_date`  datetime NOT NULL,
                `trx_no`  varchar(20) NOT NULL,
                `trx_desc`  varchar(50) NOT NULL,
                `relasi`  varchar(50) NOT NULL,
                `price`  int(11) NOT NULL DEFAULT 0,
                `awal`  decimal(11,2) NOT NULL DEFAULT 0,
                `masuk`  decimal(11,2) NOT NULL DEFAULT 0,
                `keluar`  decimal(11,2) NOT NULL DEFAULT 0,
                `koreksi`  decimal(11,2) NOT NULL DEFAULT 0,
                `saldo`  decimal(11,2) NOT NULL DEFAULT 0)';
        $this->connector->CommandText = $sqx;
        $rs = $this->connector->ExecuteNonQuery();
        // get saldo awal
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,awal,relasi,price) Select a.tanggal,'-','Stok Awal',a.qty,'-',0 From t_stockopname as a";
        $sqx.= " Where a.sku = ?sku and a.outlet_id = ?outlet_id And a.op_type = 1";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();
        // get hasil produksi
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,masuk,relasi,price)";
        $sqx.= " Select b.prod_date,b.prod_no,'Hasil Produksi',a.qty,'-',a.harga From t_produksi_detail AS a Join t_produksi AS b On a.outlet_id = b.outlet_id And a.prod_no = b.prod_no";
        $sqx.= " Where a.sku = ?sku And a.outlet_id = ?outlet_id And b.prod_status = 1 And a.prod_type = 2";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get pembelian
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,masuk,relasi,price)";
        $sqx.= " Select b.stokin_date,b.stokin_no,'Pembelian',a.qty_terima,b.supp_code,a.harga From t_stokin_detail AS a Join t_stokin AS b On a.outlet_id = b.outlet_id And a.stokin_no = b.stokin_no";
        $sqx.= " Where a.sku = ?sku And a.outlet_id = ?outlet_id And b.stokin_status < 3";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get penjualan langsung
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,keluar,relasi,price)";
        $sqx.= " SELECT b.trx_time,b.trx_no,'Penjualan (Langsung)',a.qty,b.cust_code,a.harga FROM t_sale_detail AS a";
        $sqx.= " JOIN t_sale AS b ON a.trx_no = b.trx_no";
        $sqx.= " Where a.sku = ?sku And a.outlet_id = ?outlet_id And b.trx_status < 3";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get penjualan dalam resep
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,keluar,relasi,price)";
        $sqx.= " SELECT c.trx_time,c.trx_no,'Penjualan (In Resep)',a.qty * b.qty,c.cust_code,a.harga FROM m_resep AS a";
        $sqx.= " JOIN t_sale_detail AS b ON a.outlet_id = b.outlet_id AND a.sku_utama = b.sku JOIN t_sale AS c ON b.trx_no = c.trx_no";
        $sqx.= " Where a.sku = ?sku and a.outlet_id = ?outlet_id And c.trx_status < 3";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get penjualan dalam resep dan dalam modifier
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,keluar,relasi,price)";
        $sqx.= " SELECT d.trx_time,d.trx_no,'Penjualan (In Modifier & Resep)',(a.qty * b.qty) * c.qty,d.cust_code,a.harga FROM m_resep AS a";
        $sqx.= " JOIN m_modifier AS b ON a.outlet_id = b.outlet_id AND a.sku_utama = b.sku JOIN t_sale_detail AS c ON b.outlet_id = c.outlet_id AND b.sku_utama = c.sku";
        $sqx.= " JOIN t_sale AS d ON c.trx_no = d.trx_no Where a.sku = ?sku And a.outlet_id = ?outlet_id And d.trx_status < 3";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get penjualan ke outlet (khusus pusat)
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,keluar,relasi,price)";
        $sqx.= " Select b.trx_date,b.trx_no,concat('Penjualan Ke Outlet PO: ',b.ex_po_no),a.qty_kirim,c.kode,a.harga From t_salepusat_detail AS a Join t_salepusat AS b On a.outlet_id = b.outlet_id And a.trx_no = b.trx_no";
        $sqx.= " Join m_outlet AS c On b.by_outlet_id = c.id";
        $sqx.= " Where a.sku = ?sku And a.outlet_id = ?outlet_id And (b.trx_status > 0 And b.trx_status < 3)";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get untuk produksi
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,keluar,relasi,price)";
        $sqx.= " Select b.prod_date,b.prod_no,'Untuk Produksi',a.qty,'-',a.harga From t_produksi_detail AS a Join t_produksi AS b On a.outlet_id = b.outlet_id And a.prod_no = b.prod_no";
        $sqx.= " Where a.sku = ?sku And a.outlet_id = ?outlet_id And b.prod_status = 1 And a.prod_type = 1";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // get koreksi
        $sqx = "Insert Into `tmp_card` (trx_date,trx_no,trx_desc,koreksi,relasi,price) Select a.tanggal,'-','Koreksi',a.qty,'-',0 From t_stockopname as a";
        $sqx.= " Where a.sku = ?sku and a.outlet_id = ?outlet_id And a.op_type = 2";
        $this->connector->CommandText = $sqx;
        $this->connector->AddParameter("?sku", $sku, "char");
        $this->connector->AddParameter("?outlet_id", $outletId);
        $rs = $this->connector->ExecuteNonQuery();

        // try get all tmp card data
        $sqx = "Select a.* From tmp_card AS a Order By a.trx_date,a.trx_no";
        $this->connector->CommandText = $sqx;
        return $this->connector->ExecuteQuery();
    }
}


// End of File: estimasi.php
