<?php
 /** @var $outlets Outlet[] */
 $uid = AclManager::GetInstance()->GetCurrentUser()->Id;
 $uname = AclManager::GetInstance()->GetCurrentUser()->RealName;
 $persistence = PersistenceManager::GetInstance();
 $userpic = $persistence->LoadState("user_pic");
 $userpic = base_url($userpic);
 $uOutletKode = $persistence->LoadState("outlet_kode");
 $uOutletName = $persistence->LoadState("outlet_name");
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="main" class="site_title"><i class="fa fa-paw"></i> <span>EraPOS</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <div class="separator"></div>
                    <li><a  href="<?php echo site_url("main"); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                    <li><a  href="<?php echo site_url("master/outlet"); ?>"><i class="fa fa-files-o"></i> Outlet</a></li>

                    <li><a><i class="fa fa-product-hunt"></i> Produk <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("master/produk"); ?>">Produk</a></li>
                            <li><a href="<?php echo site_url("master/kategori"); ?>">Kategori</a></li>
                        </ul>
                    </li>

                    <li><a  href="<?php echo site_url("master/customer"); ?>"><i class="fa fa-users"></i> Customer</a></li>

                    <li><a  href="<?php echo site_url("trx/sale"); ?>"><i class="fa fa-shopping-cart"></i> Penjualan</a></li>

                    <li><a><i class="fa fa-tags"></i> Inventory <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("inventory/supplier"); ?>">Supplier</a></li>
                            <li><a href="<?php echo site_url("inventory/po"); ?>">Pemesanan Bahan</a></li>
                            <li><a href="<?php echo site_url("inventory/stokin"); ?>">Penerimaan Bahan (Masuk)</a></li>
                            <li><a href="<?php echo site_url("inventory/card"); ?>">Kartu Stok</a></li>
                        </ul>
                    </li>

                    <li><a><i class="fa fa-bar-chart-o"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("report/sales"); ?>">Penjualan</a></li>
                            <li><a href="<?php echo site_url("report/purchase"); ?>">Pembelian</a></li>
                            <li><a href="<?php echo site_url("report/inventory"); ?>">Inventory</a></li>
                            <li><a href="<?php echo site_url("report/profit"); ?>">Profit</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>Pengaturan</h3>
                <div class="separator"></div>
                <ul class="nav side-menu">
                    <li><a href="<?php echo site_url("sys/useradmin"); ?>"><i class="fa fa-user"></i> Users & Staf</a></li>
                    <li><a href="#"><i class="fa fa-support"></i> Setting</a></li>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo site_url("home/logout"); ?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>

<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php print($userpic);?>" alt="">
                        <?php print($uname);?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="javascript:;"> Profile</a></li>
                        <li>
                            <a href="javascript:;">
                                <span class="badge bg-red pull-right">50%</span>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li><a href="javascript:;">Help</a></li>
                        <li><a href="<?php echo site_url("home/logout"); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>
                <?php if (isset($outlets)) {
                    print('<li class="">');
                    if (isset($userLevel)){
                       if ($userLevel > 3) {
                           print('<select id="outletSetId" class="input-sm span2 navbar-btn">');
                           print('<option value="0">All Outlet</option>');
                       }else{
                           print('<select id="outletSetId" class="input-sm span2 navbar-btn" disabled>');
                       }
                    }else {
                        print('<select id="outletSetId" class="input-sm span2 navbar-btn" disabled>');
                    }
                    foreach ($outlets as $out) {
                        if ($outletId == $out->Id) {
                            printf('<option value="%s" selected="selected">%s - %s</option>', $out->Id . '|' . $out->Kode . '|' . $out->OutletName . '|', $out->Kode, $out->OutletName);
                        } else {
                            printf('<option value="%s">%s - %s</option>', $out->Id . '|' . $out->Kode . '|' . $out->OutletName . '|', $out->Kode, $out->OutletName);
                        }
                    }
                    print('</select>');
                    if (isset($userLevel) && $userLevel > 3) {
                        print('<button id="btSetOutlet" type="button" class="btn btn-sm btn-primary navbar-btn">Set<button>');
                    }
                    print('</li>');
                } ?>
            </ul>
        </nav>
    </div>
</div>