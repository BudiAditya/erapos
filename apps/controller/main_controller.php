<?php

class MainController extends AppController {
    private $userCompanyId;
    private $userOutletId;
    private $userOutletCode;
    private $userLevel;
    private $userUid;
    private $userName;
    private $chartLabels;
    private $chartCosts;
    private $chartRevenues;

	protected function Initialize() {
        $this->userCompanyId = $this->persistence->LoadState("entity_id");
        $this->userOutletId = $this->persistence->LoadState("outlet_id");
        $this->userOutletCode = $this->persistence->LoadState("outlet_kode");
        $this->userLevel = $this->persistence->LoadState("user_lvl");
        $this->userUid = AclManager::GetInstance()->GetCurrentUser()->Id;
        $this->userName = AclManager::GetInstance()->GetCurrentUser()->RealName;
    }

	public function index()
    {
        require_once(MODEL . "master/company.php");
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "trx/sale.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "master/customer.php");
        require_once(MODEL . "inventory/po.php");
        require_once(MODEL . "inventory/stokin.php");
        require_once(MODEL . "inventory/card.php");
        //get acl rules
        $acl = AclManager::GetInstance();
        // load data for dashboard
        $loader = new Company($this->userCompanyId);
        $flogo = $loader->Flogo;
        $this->Set("flogo", $flogo);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("userLevel", $this->userLevel);
        //load outlet info
        $outlet = new Outlet($this->userOutletId);
        $this->Set("outlet", $outlet);
        //get and send data to dashboard
        $cdate = date('Y-m-d');
        $cyear = date('Y');
        $cmonth = date('m');
        $pyear = $cyear;
        $pmonth = $cmonth - 1;
        if ($cmonth == 1) {
            $pyear--;
            $pmonth = 12;
        }
        //get total active outlet
        if ($this->userOutletId == 1) {
            $loader = new Outlet();
            $ocnt = $loader->GetOutletCount($this->userCompanyId, 1);
            $this->Set("outletCnt", $ocnt);
        } else {
            $this->Set("outletCnt", 1);
        }
        //get summary sales today
        $loader = new Sale();
        if ($this->userOutletId == 1) {
            $cdsale = $loader->GetSalesSummaryToDay(0, $cdate);
            $cdate = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $cdate) ) ));
            $ldsale = $loader->GetSalesSummaryToDay(0, $cdate);
        } else {
            $cdsale = $loader->GetSalesSummaryToDay($this->userOutletId, $cdate);
            $cdate = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $cdate) ) ));
            $ldsale = $loader->GetSalesSummaryToDay($this->userOutletId, $cdate);
        }
        if ($cdsale > 0 && $ldsale > 0) {
            $tdspercentage = round((($cdsale - $ldsale) / $ldsale) * 100, 0);
        } else {
            $tdspercentage = 0;
        }

        $cdsale = round($cdsale / 1000, 0);
        $this->Set("toDaySale", $cdsale);
        $this->Set("toDayPercentage", $tdspercentage);
        //get average sales
        $avpercentage = 0;
        if ($this->userOutletId == 1) {
            $avpsale = $loader->GetDailySaleAverageGlobal($pyear, $pmonth);
            $avcsale = $loader->GetDailySaleAverageGlobal($cyear, $cmonth);
        } else {
            $avpsale = $loader->GetDailySaleAverageOutlet($this->userOutletId, $pyear, $pmonth);
            $avcsale = $loader->GetDailySaleAverageOutlet($this->userOutletId, $cyear, $cmonth);
        }
        if ($avcsale > 0 && $avpsale > 0) {
            $avpercentage = round((($avcsale - $avpsale) / $avpsale) * 100, 0);
        } else {
            $avpercentage = 0;
        }
        $avcsale = round($avcsale / 1000, 0);
        $this->Set("avgDailySale", $avcsale);
        $this->Set("avgPercentage", $avpercentage);
        //get sum sales by month
        $sumpercentage = 0;
        if ($this->userLevel > 3) {
            $sumpsale = $loader->GetSalesSummaryGlobal($pyear, $pmonth);
            $sumcsale = $loader->GetSalesSummaryGlobal($cyear, $cmonth);
        } else {
            $sumpsale = $loader->GetSalesSummaryOutlet($this->userOutletId, $pyear, $pmonth);
            $sumcsale = $loader->GetSalesSummaryOutlet($this->userOutletId, $cyear, $cmonth);
        }
        if ($sumcsale > 0 && $sumpsale > 0) {
            $sumpercentage = round((($sumcsale - $sumpsale) / $sumpsale) * 100, 0);
        } else {
            $sumpercentage = 0;
        }
        $sumcsale = round($sumcsale / 1000, 0);
        $this->Set("sumMonthlySale", $sumcsale);
        $this->Set("sumPercentage", $sumpercentage);
        //get produk data
        $loader = new Produk();
        if ($this->userLevel > 2) {
            $prdCount = $loader->GetProdukSaleItem();
        }else{
            $prdCount = $loader->GetProdukSaleItem($this->userOutletId);
        }
        $this->Set("produkCount", $prdCount);
        //get customer data
        $loader = new Customer();
        $cstCount = $loader->GetCustomerCount($this->userOutletId);
        $this->Set("customerCount", $cstCount);
        //get pending transaction
        $pSaleCnt = 0;
        $pPoCnt = 0;
        $pStiCnt = 0;
        $loader = new Sale();
        $pSaleCnt = $loader->GetCountPendingSale($this->userOutletId);
        $loader = new Po();
        $pPoCnt = $loader->GetCountPendingPo($this->userOutletId);
        $loader = new StokIn();
        if ($this->userLevel > 1){
            $pStiCnt = $loader->GetCountPendingStokIn($this->userOutletId);
        }
        $this->Set("pSaleCnt", $pSaleCnt);
        $this->Set("pPoCnt", $pPoCnt);
        $this->Set("pStiCnt", $pStiCnt);
        //get sales percentage by outlet
        $loader = new Sale();
        $pctsale = $loader->GetSalesPercentage();
        $this->Set("rsPctSale", $pctsale);
        //get top 5 sale
        $top5 = $loader->GetTop5SaleByQty();
        $this->Set("rsTop5Sale", $top5);
        //get inventory card
        $loader = new Card();
        $stokCard = $loader->LoadByOutlet($this->userOutletId);
        $this->Set("stokCard", $stokCard);
	}

    public function dashboard()
    {
        require_once(MODEL . "master/company.php");
        require_once(MODEL . "master/outlet.php");
        require_once(MODEL . "trx/sale.php");
        require_once(MODEL . "master/produk.php");
        require_once(MODEL . "master/customer.php");
        require_once(MODEL . "inventory/po.php");
        require_once(MODEL . "inventory/stokin.php");
        require_once(MODEL . "inventory/card.php");
        //get acl rules
        $acl = AclManager::GetInstance();
        // load data for dashboard
        $loader = new Company($this->userCompanyId);
        $flogo = $loader->Flogo;
        $this->Set("flogo", $flogo);
        $this->Set("outletId", $this->userOutletId);
        $this->Set("userLevel", $this->userLevel);
        //get and send data to dashboard
        $cdate = date('Y-m-d');
        $cyear = date('Y');
        $cmonth = date('m');
        $pyear = $cyear;
        $pmonth = $cmonth - 1;
        if ($cmonth == 1) {
            $pyear--;
            $pmonth = 12;
        }
        //get total active outlet
        if ($this->userLevel > 3) {
            $loader = new Outlet();
            $ocnt = $loader->GetOutletCount($this->userCompanyId, 1);
            $this->Set("outletCnt", $ocnt);
        } else {
            $this->Set("outletCnt", 1);
        }
        //get summary sales today
        $loader = new Sale();
        if ($this->userLevel > 3) {
            $cdsale = $loader->GetSalesSummaryToDay(0, $cdate);
            $cdate = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $cdate) ) ));
            $ldsale = $loader->GetSalesSummaryToDay(0, $cdate);
        } else {
            $cdsale = $loader->GetSalesSummaryToDay($this->userOutletId, $cdate);
            $cdate = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $cdate) ) ));
            $ldsale = $loader->GetSalesSummaryToDay($this->userOutletId, $cdate);
        }
        if ($cdsale > 0 && $ldsale > 0) {
            $tdspercentage = round((($cdsale - $ldsale) / $ldsale) * 100, 0);
        } else {
            $tdspercentage = 0;
        }

        $cdsale = round($cdsale / 1000, 0);
        $this->Set("toDaySale", $cdsale);
        $this->Set("toDayPercentage", $tdspercentage);
        //get average sales
        $avpercentage = 0;
        if ($this->userLevel > 3) {
            $avpsale = $loader->GetDailySaleAverageGlobal($pyear, $pmonth);
            $avcsale = $loader->GetDailySaleAverageGlobal($cyear, $cmonth);
        } else {
            $avpsale = $loader->GetDailySaleAverageOutlet($this->userOutletId, $pyear, $pmonth);
            $avcsale = $loader->GetDailySaleAverageOutlet($this->userOutletId, $cyear, $cmonth);
        }
        if ($avcsale > 0 && $avpsale > 0) {
            $avpercentage = round((($avcsale - $avpsale) / $avpsale) * 100, 0);
        } else {
            $avpercentage = 0;
        }
        $avcsale = round($avcsale / 1000, 0);
        $this->Set("avgDailySale", $avcsale);
        $this->Set("avgPercentage", $avpercentage);
        //get sum sales by month
        $sumpercentage = 0;
        if ($this->userLevel > 3) {
            $sumpsale = $loader->GetSalesSummaryGlobal($pyear, $pmonth);
            $sumcsale = $loader->GetSalesSummaryGlobal($cyear, $cmonth);
        } else {
            $sumpsale = $loader->GetSalesSummaryOutlet($this->userOutletId, $pyear, $pmonth);
            $sumcsale = $loader->GetSalesSummaryOutlet($this->userOutletId, $cyear, $cmonth);
        }
        if ($sumcsale > 0 && $sumpsale > 0) {
            $sumpercentage = round((($sumcsale - $sumpsale) / $sumpsale) * 100, 0);
        } else {
            $sumpercentage = 0;
        }
        $sumcsale = round($sumcsale / 1000, 0);
        $this->Set("sumMonthlySale", $sumcsale);
        $this->Set("sumPercentage", $sumpercentage);
        //get produk data
        $loader = new Produk();
        if ($this->userLevel > 3) {
            $prdCount = $loader->GetProdukSaleItem();
        }else{
            $prdCount = $loader->GetProdukSaleItem($this->userOutletId);
        }
        $this->Set("produkCount", $prdCount);
        //get customer data
        $loader = new Customer();
        if ($this->userLevel > 3) {
            $cstCount = $loader->GetCustomerCount();
        }else{
            $cstCount = $loader->GetCustomerCount($this->userOutletId);
        }
        $this->Set("customerCount", $cstCount);
        //get pending transaction
        $pSaleCnt = 0;
        $pPoCnt = 0;
        $pStiCnt = 0;
        $loader = new Sale();
        $pSaleCnt = $loader->GetCountPendingSale($this->userOutletId);
        $loader = new Po();
        if ($this->userLevel > 3){
            $pPoCnt = $loader->GetCountPendingPo();
        }else{
            $pPoCnt = $loader->GetCountPendingPo($this->userOutletId);
        }
        $loader = new StokIn();
        if ($this->userLevel > 3){
            $pStiCnt = $loader->GetCountPendingStokIn();
        }else{
            $pStiCnt = $loader->GetCountPendingStokIn($this->userOutletId);
        }
        $this->Set("pSaleCnt", $pSaleCnt);
        $this->Set("pPoCnt", $pPoCnt);
        $this->Set("pStiCnt", $pStiCnt);
        //get sales percentage by outlet
        $loader = new Sale();
        $pctsale = $loader->GetSalesPercentage();
        $this->Set("rsPctSale", $pctsale);
        //get top 5 sale
        $top5 = $loader->GetTop5SaleByQty();
        $this->Set("rsTop5Sale", $top5);
        //get inventory card
        $loader = new Card();
        $stokCard = $loader->LoadByOutlet($this->userOutletId);
        $this->Set("stokCard", $stokCard);
    }

	public function impersonate($outletId) {
		$ulevel = $this->persistence->LoadState("user_lvl");
		if ($ulevel < 4) {
			$this->persistence->SaveState("error", "Maaf Anda tidak diperkenankan mengakses outlet ini!");
			redirect_url("main");
		}

		$this->persistence->SaveState("outlet_id", $outletId);

		$referer = $_SERVER["HTTP_REFERER"];
		if ($referer != null) {
			Dispatcher::Redirect($referer);
		} else {
			redirect_url("main");
		}
	}

	public function change_password() {
		if ($this->persistence->StateExists("info")) {
			$this->Set("info", $this->persistence->LoadState("info"));
			$this->persistence->DestroyState("info");
		}

		if (count($this->postData) == 0) {
			return;
		}

		// OK mari kita ganti passwordnya
		$old = $this->GetPostValue("Old");
		$new = $this->GetPostValue("New");
		$retype = $this->GetPostValue("Retype");

		if ($old == "") {
			$this->Set("error", "Maaf mohon mengetikkan password lama anda");
			return;
		}
		if ($new == "") {
			$this->Set("error", "Maaf mohon mengetikkan password baru anda");
			return;
		}
		if ($new == $old) {
			$this->Set("error", "Password lama dan password baru sama.");
			return;
		}
		if ($new != $retype) {
			$this->Set("error", "Password baru dan ulangi tidak sama");
			return;
		}

		$old = md5($old);
		$new = md5($new);

		$this->connector->CommandText = "UPDATE sys_users SET user_pwd = ?new WHERE user_uid = ?id AND user_pwd = ?old";
		$this->connector->AddParameter("?new", $new);
		$this->connector->AddParameter("?id", AclManager::GetInstance()->GetCurrentUser()->Id);
		$this->connector->AddParameter("?old", $old);

		$rs = $this->connector->ExecuteNonQuery();
		if ($rs == 1) {
			$this->persistence->SaveState("info", "Password anda telah berhasil dirubah. Password baru akan efektif pada login berikutnya.");
			redirect_url("main/change_password");
		} else {
			$this->Set("error", "Maaf password lama anda salah.");
		}
	}

	public function set_periode() {
		if (count($this->postData) > 0) {
			$year = $this->GetPostValue("year");
			$month = $this->GetPostValue("month");

			$this->persistence->SaveState("acc_year", $year);
			$this->persistence->SaveState("acc_month", $month);

			// OK karena simpan persistence sifatnya void kita asumsikan berhasil
			redirect_url("main");
		} else {
			if ($this->persistence->StateExists("acc_year")) {
				$year = $this->persistence->LoadState("acc_year");
			} else {
				$year = date("Y");
			}
			if ($this->persistence->StateExists("acc_month")) {
				$month = $this->persistence->LoadState("acc_month");
			} else {
				$month = date("n");
			}

		}

		$this->Set("year", $year);
		$this->Set("month", $month);

		if ($this->persistence->StateExists("error")) {
			$this->Set("error", $this->persistence->LoadState("error"));
			$this->persistence->DestroyState("error");
		}
	}

	public function aclview($uid = 0) {
		//load acl
		require_once(MODEL . "sys/user_admin.php");
		require_once(MODEL . "master/user_acl.php");
		if ($uid == 0){
			$uid = AclManager::GetInstance()->GetCurrentUser()->Id;
		}
		$userId = null;
		$userdata = new UserAdmin();
		$userdata = $userdata->FindById($uid);
		$userId = $userdata->UserId.' ['.$userdata->UserName.']';
		$userAcl = new UserAcl();
		$aclists = $userAcl->GetUserAclList($uid);
		$this->Set("userId", $userId);
		$this->Set("aclists", $aclists);
	}
}
