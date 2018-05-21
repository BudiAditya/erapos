<?php
class CustomerController extends AppController {
	private $userCompanyId;
	private $userOutletId;
	private $userLevel;

	protected function Initialize() {
		require_once(MODEL . "master/customer.php");
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
        $customer = new Customer();
        $log = new UserAdmin();
        $result['crud'] = 'N';
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $customer->Nama = $this->GetPostValue("Nama");
            $customer->Kota = $this->GetPostValue("Kota");
            $customer->Alamat = $this->GetPostValue("Alamat");
            $customer->Phone = $this->GetPostValue("Phone");
            $customer->Email = $this->GetPostValue("Email");
            $customer->OutletId = $this->userOutletId;
            $customer->Kode = $customer->GetCustomerCode($customer->OutletId);
            $customer->CreatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($customer->Insert() == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Add New Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $customer->Kode);
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
        $customer = new Customer();
        $log = new UserAdmin();
        $result['crud'] = 'E';
        $result['error'] = '';
        $result['result'] = 0;
        $Crud = null;
        if (count($this->postData) > 0) {
            // OK user ada kirim data kita proses
            $customer->Id = $this->GetPostValue("Id");
            $customer->Kode = $this->GetPostValue("Kode");
            $customer->Nama = $this->GetPostValue("Nama");
            $customer->Kota = $this->GetPostValue("Kota");
            $customer->Alamat = $this->GetPostValue("Alamat");
            $customer->Phone = $this->GetPostValue("Phone");
            $customer->Email = $this->GetPostValue("Email");
            $customer->OutletId = $this->userOutletId;
            $customer->UpdatebyId = AclManager::GetInstance()->GetCurrentUser()->Id;
            $Crud = $this->GetPostValue("Crud");
            if ($customer->Update($customer->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.customer','Update Data Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.customer','Update Data Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Failed');
                if ($this->connector->GetHasError()) {
                    if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
                        $result['error'] = printf("Kode: '%s' telah ada pada database !", $customer->Kode);
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
	    $customer = new Customer();
	    $customer = $customer->LoadById($id);
	    $data = array();
	    if ($customer != null){
            $data['id'] = $customer->Id;
            $data['kode'] = $customer->Kode;
            $data['nama'] = $customer->Nama;
            $data['alamat'] = $customer->Alamat;
            $data['kota'] = $customer->Kota;
            $data['email'] = $customer->Email;
            $data['phone'] = $customer->Phone;
            $data['outlet_id'] = $customer->OutletId;
        }
        print json_encode($data);
    }

	public function delete() {
	    $id = $_POST['id'];
		$log = new UserAdmin();
		$customer = new Customer();
		$customer = $customer->FindById($id);
		if ($customer == null) {
            $result['error'] = 'Data Customer yang dipilih tidak ditemukan!';
            $result['result'] = 0;
		}else{
            if ($customer->Delete($customer->Id) == 1) {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Success');
                $result['error'] = '';
                $result['result'] = 1;
            } else {
                $log = $log->UserActivityWriter($this->userOutletId,'master.Outlet','Delete Outlet -> Kode: '.$customer->Kode.' - '.$customer->Nama,'-','Failed');
                $result['error'] = printf("Gagal menghapus data outlet: '%s'. Message: %s", $customer->Kode, $this->connector->GetErrorMessage());
                $result['result'] = 0;
            }
        }
		print json_encode($result);
	}

	public function getJsonCustomer(){
	    $customer = new Customer();
	    $customerLists = $customer->GetJsonCustomer($this->userOutletId);
	    print json_encode($customerLists);
    }
}
