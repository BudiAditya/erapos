<?php
class KasController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "trx/kas.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
		//load data to datatables
        $this->Set("userLevel",$this->userLevel);
        $this->Set("outletId",$this->userOutletId);
	}

	public function add1(){
        $result['crud'] = 'N';
        $result['error'] = 'No Data Posted!';
        $result['result'] = 0;
        print json_encode($result);
    }

	public function add() {
        $kas = new Kas();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $kas->OutletId = $this->userOutletId;
            $kas->TrxDate = strtotime($this->GetPostValue("TrxDate"));
            $kas->TrxType = $this->GetPostValue("TrxType");
            $kas->Notes = $this->GetPostValue("Notes");
            $kas->Jumlah = $this->GetPostValue("Jumlah");
            $kas->TrxNo = $kas->AutoTrxNo($kas->OutletId,$kas->TrxDate);
            $kas->TrxMode = 1;
            $kas->TrxStatus = 2;
            $kas->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($kas->Insert() == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Kas -> '.$kas->Kas,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
               // $log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Add New Kas ->  '.$kas->Kas,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Trx No: '%s' telah ada pada database !", $kas->TrxNo);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
            }
        }else{
            $result['error'] = 'No Data Posted!';
            $result['result'] = 0;
        }
        print json_encode($result);
    }

    public function edit() {
        $kas = new Kas();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $result['error'] = '';
        $result['result'] = 0;
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $kas->Id = $this->GetPostValue("Id");
            $kas->Kas = $this->GetPostValue("Kas");
            $kas->EntityId = 1;
            $kas->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($kas->Update($kas->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Kas -> '.$kas->Kas,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.outlet','Update Data Kas -> '.$kas->Kas,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $kas->Kas);
                    } else {
                        $result['error'] = printf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage());
                    }
                }
                $result['result'] = 0;
            }
        }else{
            $result['error'] = 'No Data Posted!';
            $result['result'] = 0;
        }
        print json_encode($result);
    }

	public function getdata(){
	    $id = $_POST["id"];
	    $kas = new Kas();
	    $kas = $kas->LoadById($id);
	    $data = array();
	    if ($kas != null){
            $data['id'] = $kas->Id;
            $data['kategori'] = $kas->Kas;
            $data['entity_id'] = $kas->EntityId;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$kas = new Kas();
		$kas = $kas->FindById($id);
		if ($kas == null) {
            $result['error'] = 'Data Kas yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($kas->Delete($kas->Id) == 1) {
                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Kas -> '.$kas->Kas,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                //$log = $log->UserActivityWriter($this->userOutletId,'trx.kas','Delete Kas -> '.$kas->Kas,'-','Failed');
                $result['error'] = printf("Gagal menghapus data kas: '%s'. Message: %s", $kas->TrxNo, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonKas(){
	    $kas = new Kas();
	    $kasLists = $kas->GetJsonKas($this->userOutletId);
	    print json_encode($kasLists);
    }

    public function report(){
        require_once (MODEL . "master/outlet.php");
        $month = (int)date("n");
        $year = (int)date("Y");
        if (count($this->postData) > 0) {
            $outletId =  $this->GetPostValue("outletId");
            $startDate = strtotime($this->GetPostValue("startDate"));
            $endDate = strtotime($this->GetPostValue("endDate"));
            $jnsLaporan = $this->GetPostValue("jnsLaporan");
            $outPut = $this->GetPostValue("outPut");
            $kas = new Kas();
            if ($jnsLaporan == 1) {
                $reports = $kas->LoadByOutletId($outletId, $startDate, $endDate);
            }else{
                $reports = $kas->Load4Rekap($outletId, $startDate, $endDate);
            }
        }else{
            $outletId = $this->userOutletId;
            $startDate = mktime(0, 0, 0, $month, 1, $year);
            $endDate = time();
            $kas = new Kas();
            $reports = $kas->LoadByOutletId($outletId,$startDate,$endDate);
            $jnsLaporan = 1;
            $outPut = 1;
        }
        //var_dump($reports);
        //exit;
        $loader = new Outlet();
        if ($this->userOutletId == 1) {
            $outlets = $loader->LoadAll($this->userCompanyId);
        }else{
            $outlets = $loader->LoadById($this->userOutletId);
        }
        $this->Set("uOutletId",$this->userOutletId);
        $this->Set("outlets",$outlets);
        $this->Set("outletId",$outletId);
        $this->Set("startDate",$startDate);
        $this->Set("endDate",$endDate);
        $this->Set("jnsLaporan",$jnsLaporan);
        $this->Set("outPut",$outPut);
        $this->Set("reports",$reports);
    }
}
