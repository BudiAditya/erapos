<?php
 /** @var $outlets Outlet[] */
 $uid = AclManager::GetInstance()->GetCurrentUser()->Id;
 $uname = AclManager::GetInstance()->GetCurrentUser()->RealName;
 $persistence = PersistenceManager::GetInstance();
 $userpic = $persistence->LoadState("user_pic");
 $userpic = base_url($userpic);
 $uOutletId = $persistence->LoadState("outlet_id");
 $uOutletKode = $persistence->LoadState("outlet_kode");
 $uOutletName = $persistence->LoadState("outlet_name");
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo site_url("main"); ?>" class="site_title"><i class="fa fa-paw"></i> <span>EraPOS</span></a>
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
                    <li><a href="<?php echo site_url("main/dashboard"); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <?php if ($uOutletId == 1){ ?>
                    <li><a href="<?php echo site_url("master/outlet"); ?>"><i class="fa fa-files-o"></i> Outlet</a></li>
                    <?php } ?>

                    <?php if ($uOutletId > 1){ ?>
                        <li><a href="<?php echo site_url("master/produk"); ?>"><i class="fa fa-tags"></i> Produk</a></li>
                        <li><a href="<?php echo site_url("master/customer"); ?>"><i class="fa fa-users"></i> Customer</a></li>
                    <?php } ?>

                    <?php if ($uOutletId > 1){ ?>
                    <li><a href="<?php echo site_url("trx/sale"); ?>"><i class="fa fa-shopping-cart"></i> Penjualan</a></li>
                    <?php }else{ ?>
                    <li><a><i class="fa fa-shopping-cart"></i> Penjualan Pusat<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("master/customer"); ?>"> Customer</a></li>
                            <li><a href="<?php echo site_url("trx/salesorder"); ?>"> Order Pesanan</a></li>
                            <li><a href="<?php echo site_url("trx/salepusat"); ?>"> Transaksi Penjualan</a></li>
                            <li><a href="<?php echo site_url("trx/receive"); ?>"> Penerimaan Piutang</a></li>
                        </ul>
                    </li>
                    <?php } ?>

                    <li><a href="<?php echo site_url("trx/kas"); ?>"><i class="fa fa-money"></i> Kelola Kas</a></li>

                    <li><a><i class="fa fa-bolt"></i> Pembelian <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("inventory/supplier"); ?>">Supplier</a></li>
                            <?php if ($uOutletId > 1){ ?>
                            <li><a href="<?php echo site_url("inventory/po"); ?>">Pemesanan Barang</a></li>
                            <?php } ?>
                            <li><a href="<?php echo site_url("inventory/stokin"); ?>">Penerimaan Barang</a></li>
                            <li><a href="<?php echo site_url("trx/payment"); ?>">Pembayaran Hutang</a></li>
                        </ul>
                    </li>
                    <?php if ($uOutletId == 1){ ?>
                    <li><a><i class="fa fa-tags"></i> Inventory <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo site_url("master/kategori"); ?>">Kategori</a></li>
                            <li><a href="<?php echo site_url("master/produk"); ?>">Produk</a></li>
                            <li><a href="<?php echo site_url("inventory/produksi"); ?>">Produksi</a></li>
                            <li><a href="<?php echo site_url("inventory/card"); ?>">Kartu Stok</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if ($uOutletId == 1){ ?>
                    <li><a><i class="fa fa-pie-chart"></i> Laporan Pusat<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                                <li><a href="<?php echo site_url("trx/kas/report"); ?>">Laporan Kas</a></li>
                                <li><a href="<?php echo site_url("trx/salepusat/report"); ?>"> Penjualan Pusat</a></li>
                                <li><a href="<?php echo site_url("trx/sale/report"); ?>"> Penjualan Outlet</a></li>
                                <li><a href="<?php echo site_url("trx/receive/report"); ?>"> Penerimaan Piutang</a></li>
                        </ul>
                    </li>
                    <?php }else{ ?>
                        <li><a><i class="fa fa-pie-chart"></i> Laporan<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="<?php echo site_url("trx/kas/report"); ?>">Laporan Kas</a></li>
                                <li><a href="<?php echo site_url("trx/sale/report"); ?>"> Laporan Penjualan</a></li>
                                <li><a href="<?php echo site_url("inventory/stokin/report"); ?>">Laporan Pembelian</a></li>
                                <li><a href="<?php echo site_url("trx/payment/report"); ?>">Laporan Hutang</a></li>
                                <li><a href="<?php echo site_url("inventory/card"); ?>">Kartu Stok</a></li>
                            </ul>
                        </li>
                    <?php } ?>
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
                <li class="">
                    <span class="span2 navbar-btn">
                        <select id="outletSetId" class="input-sm span2 navbar-btn" disabled style="font-weight: bold; color: #00A000">
                            <?php
                            printf('<option value="%d">%s - %s</option>',$uOutletId,$uOutletKode,$uOutletName)
                            ?>
                        </select>
                    </span>
                </li>
            </ul>
        </nav>
    </div>
</div>