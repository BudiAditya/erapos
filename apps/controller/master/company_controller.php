<?php
class CompanyController extends AppController {
	private $userCompanyId;
	private $userCabangId;
	private $userUid;

	protected function Initialize() {
		require_once(MODEL . "master/company.php");
		require_once(MODEL . "sys/user_admin.php");
		$this->userCompanyId = $this->persistence->LoadState("entity_id");
		$this->userCabangId = $this->persistence->LoadState("cabang_id");
        $this->userUid = AclManager::GetInstance()->GetCurrentUser()->Id;
	}

	public function index() {
		$loader = null;
		$info = null;
		$company = new Company();
		$log = new UserAdmin();
		if (count($this->postData) > 0) {
			// OK user ada kirim data kita proses
            $company->Urutan = 1;
			$company->EntityId = $this->userCompanyId;
            $company->EntityCd = $this->GetPostValue("EntityCd");
            $company->CompanyName = $this->GetPostValue("CompanyName");
            $company->Address = $this->GetPostValue("Address");
            $company->Province = $this->GetPostValue("Province");
            $company->City = $this->GetPostValue("City");
            $company->Telephone = $this->GetPostValue("Telephone");
            $company->PersonInCharge = $this->GetPostValue("PersonInCharge");
            $company->StartDate = strtotime($this->GetPostValue("StartDate"));
            $company->UpdatebyId = $this->userUid;
            $company->Flogo = $this->GetPostValue("Flogo");
            //upload image if avalilable
            if (!empty($_FILES['Flogo']['tmp_name'])) {
                $fpath = 'public/upload/company/';
                $ftmp = $_FILES['Flogo']['tmp_name'];
                $fname = $_FILES['Flogo']['name'];
                $fpath.= $fname;
                if(move_uploaded_file($ftmp,$fpath)){
                    $company->Flogo = $fpath;
                }
            }else{
                $company->Flogo = null;
            }
			if ($this->DoUpdate($company)) {
				$log = $log->UserActivityWriter($this->userCabangId,'master.company','Update Company -> KOde: '.$company->EntityCd,'-','Success');
				$this->persistence->SaveState("info", sprintf("Data Company: '%s' berhasil diupdate.", $company->EntityCd));
				$info = sprintf("Data Company: '%s' berhasil diupdate.", $company->EntityCd);
			} else {
				if ($this->connector->GetHasError()) {
					if ($this->connector->GetErrorCode() == $this->connector->GetDuplicateErrorCode()) {
						$this->Set("error", sprintf("Company Kode: '%s' telah ada pada database !", $company->EntityCd));
					} else {
						$this->Set("error", sprintf("System Error: %s. Please Contact System Administrator.", $this->connector->GetErrorMessage()));
					}
				}
			}
		} else {
			$company = $company->FindById($this->userCompanyId);
			if ($company == null) {
				$this->persistence->SaveState("error", "Data Company yang dipilih tidak ditemukan ! Mungkin data sudah dihapus.");
			}
		}
        $this->Set("info", $info);
        $this->Set("company", $company);
	}

	private function DoUpdate(Company $company) {
        if ($company->EntityCd == "") {
            $this->Set("error", "Kode Company masih kosong");
            return false;
        }
		if ($company->CompanyName == "") {
			$this->Set("error", "Nama Company masih kosong");
			return false;
		}
		if ($company->Update($company->EntityId) == 1) {
			return true;
		} else {
			return false;
		}
	}
}
