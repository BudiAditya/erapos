<?php
class CardController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/card.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
	    //load data to datatables
        $cards = new Card();
        $cards = $cards->LoadByOutlet($this->userOutletId);
        $this->Set("cards",$cards);
        $this->Set("outletId",$this->userOutletId);
	}

    public function view($sku = null){
        require_once(MODEL . "master/produk.php");
        //get sku data
        $produk = new Produk();
        $produk = $produk->FindBySku($sku,$this->userOutletId);
        $this->Set("sku",$sku);
        $this->Set("produk_name",$produk->Nama);
        $this->Set("produk_satuan",$produk->Satuan);
        //load inventory card data
        $cards = new Card();
        $cards = $cards->GetStockHistory($this->userOutletId,$sku);
        $this->Set("cards",$cards);
        $this->Set("outletId",$this->userOutletId);
    }
}
