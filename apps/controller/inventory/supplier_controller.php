<?php
class SupplierController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "inventory/supplier.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userOutletId = $this->persistence->LoadState("outlet_id");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userLevel = $this->persistence->LoadState("user_lvl");
	}

	public function index() {
		//load data to datatables
        $this->Set("outletId",$this->userOutletId);
	}

	public function add() {
        $supplier = new Supplier();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $supplier->Nama = $this->GetPostValue("Nama");
            $supplier->Kota = $this->GetPostValue("Kota");
            $supplier->Alamat = $this->GetPostValue("Alamat");
            $supplier->Phone = $this->GetPostValue("Phone");
            $supplier->Email = $this->GetPostValue("Email");
            $supplier->OutletId = $this->GetPostValue("OutletId");
            $supplier->Kode = $supplier->GetSupplierCode($supplier->OutletId);
            $supplier->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($supplier->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $supplier->Kode);
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
        $supplier = new Supplier();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $result['error'] = '';
        $result['result'] = 0;
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $supplier->Id = $this->GetPostValue("Id");
            $supplier->Kode = $this->GetPostValue("Kode");
            $supplier->Nama = $this->GetPostValue("Nama");
            $supplier->Kota = $this->GetPostValue("Kota");
            $supplier->Alamat = $this->GetPostValue("Alamat");
            $supplier->Phone = $this->GetPostValue("Phone");
            $supplier->Email = $this->GetPostValue("Email");
            $supplier->OutletId = $this->GetPostValue("OutletId");
            $supplier->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($supplier->Update($supplier->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.supplier','Update Data Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.supplier','Update Data Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $supplier->Kode);
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
	    $supplier = new Supplier();
	    $supplier = $supplier->LoadById($id);
	    $data = array();
	    if ($supplier != null){
            $data['id'] = $supplier->Id;
            $data['kode'] = $supplier->Kode;
            $data['nama'] = $supplier->Nama;
            $data['alamat'] = $supplier->Alamat;
            $data['kota'] = $supplier->Kota;
            $data['email'] = $supplier->Email;
            $data['phone'] = $supplier->Phone;
            $data['outlet_id'] = $supplier->OutletId;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$supplier = new Supplier();
		$supplier = $supplier->FindById($id);
		if ($supplier == null) {
            $result['error'] = 'Data Supplier yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($supplier->Delete($supplier->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$supplier->Kode.' - '.$supplier->Nama,'-','Failed');
                $result['error'] = printf("Gagal menghapus data outlet: '%s'. Message: %s", $supplier->Kode, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonSupplier(){
	    $supplier = new Supplier();
	    $supplierLists = $supplier->GetJsonSupplier($this->userCompanyId);
	    print json_encode($supplierLists);
    }
}
