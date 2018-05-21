<!DOCTYPE html>
<?php
/** @var $outlet Outlet */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Dashboard </title>

    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php print($helper->path("assets/vendors/nprogress/nprogress.css"));?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php print($helper->path("assets/vendors/iCheck/skins/flat/green.css"));?>" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"));?>" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php print($helper->path("assets/vendors/jqvmap/dist/jqvmap.min.css"));?>" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap-daterangepicker/daterangepicker.css"));?>" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
</head>

<body class="nav-sm">
<div class="container body">
    <div class="main_container">
        <!-- menu & navigation -->
        <?php include(VIEW . "main/menu.php"); ?>
        <!-- /top navigation -->
        <?php if ($outlet->Id > 1) { ?>
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="row" align="center">
                    <img src="<?php print(base_url('public/images/agpg-logo.jpg')); ?>" width="350" height="300">
                    <p>
                        <b>
                            <font size="5">
                                <?php
                                print($outlet->OutletName);
                                ?>
                            </font>
                        </b>
                    </p>
                    <p>
                        <?php print(strtoupper($outlet->Alamat . ' ' . $outlet->Kota)); ?>
                    </p>
                </div>
            </div>
            <?php
        }   else {
            ?>
            <div class="right_col" role="main">
                <!-- top tiles -->
                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-files-o"></i> Total Outlet</span>
                        <div class="count"><?php print($outletCnt);?></div>
                        <span class="count_bottom"><i class="green">100% </i> Operated</span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Penjualan hari ini</span>
                        <div class="count"><?php print(number_format($toDaySale,0).'k');?></div>
                        <?php if ($toDayPercentage > 0){ ?>
                            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php print(number_format($toDayPercentage,0).'%');?> </i> From last day</span>
                        <?php }else{ ?>
                            <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?php print(number_format($toDayPercentage * -1,0).'%');?> </i> From last day</span>
                        <?php } ?>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Rata2 Penjualan /hari</span>
                        <div class="count"><?php print(number_format($avgDailySale,0).'k');?></div>
                        <?php if ($avgPercentage > 0){ ?>
                            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php print(number_format($avgPercentage,0).'%');?> </i> From last Month</span>
                        <?php }else{ ?>
                            <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?php print(number_format($avgPercentage * -1,0).'%');?> </i> From last Month</span>
                        <?php } ?>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-shopping-cart"></i> Penjualan bulan ini</span>
                        <div class="count green"><?php print(number_format($sumMonthlySale,0).'k');?></div>
                        <?php if ($sumPercentage > 0){ ?>
                            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?php print(number_format($sumPercentage,0).'%');?> </i> From last Month</span>
                        <?php }else{ ?>
                            <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?php print(number_format($sumPercentage * -1,0).'%');?> </i> From last Month</span>
                        <?php } ?>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-tags"></i> Varian Menu</span>
                        <div class="count center"><?php print(number_format($produkCount,0));?></div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>100% </i> From last Month</span>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Customer</span>
                        <div class="count center"><?php print(number_format($customerCount,0));?></div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>100% </i> From last Month</span>
                    </div>
                </div>
                <!-- /top tiles -->
                <?php
                if ($pSaleCnt + $pPoCnt + $pStiCnt > 0){
                    ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="row x_title">
                                    <div class="col-md-6">
                                        <h3>NOTIFIKASI</h3>
                                    </div>
                                </div>
                                <div class="x_content">
                                    <div class="">
                                        <ul>
                                            <?php
                                            if ($pSaleCnt > 0){
                                                printf('<li><p style="color: brown"><b>%s Transaksi Penjualan</b> masih berstatus: -OPEN-<a href="%s"><i> klik untuk review</i></a></li></p>',$pSaleCnt,site_url("trx/sale"));
                                            }
                                            if ($pPoCnt > 0){
                                                printf('<li><p style="color: brown"><b>%s Order Pesanan</b> masih berstatus: -OPEN-<a href="%s"><i> klik untuk review</i></a></li></p>',$pPoCnt,site_url("inventory/po"));
                                            }
                                            if ($pStiCnt > 0){
                                                printf('<li><p style="color: brown"><b>%s Barang Masuk</b> masih berstatus: -OPEN-<a href="%s"><i> klik untuk review</i></a></li></p>',$pStiCnt,site_url("inventory/stokin"));
                                            }
                                            ?>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                <?php } ?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph x_panel">
                            <div class="row x_title">
                                <div class="col-md-6">
                                    <h3>SALES OMSET <small>** All Outlet **</small></h3>
                                </div>
                                <div class="col-md-6">
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                                    </div>
                                </div>
                            </div>
                            <div class="x_content">
                                <div class="demo-container" style="height:250px">
                                    <div id="chart_plot_03" class="demo-placeholder"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile fixed_height_320">
                            <div class="x_title">
                                <h2>Top 5 Penjualan Outlet</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <?php
                                while ($data = $rsPctSale->FetchAssoc()){
                                    print('<div class="widget_summary">');
                                    print('<div class="w_left w_25">');
                                    printf('<span>%s</span>',$data['outlet_kode']);
                                    print('</div>');
                                    print('<div class="w_center w_55">');
                                    print('<div class="progress">');
                                    printf('<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: %s;">',$data['sale_percent'].'%');
                                    print('<span class="sr-only">60% Complete</span>');
                                    print('</div>');
                                    print('</div>');
                                    print('</div>');
                                    print('<div class="w_right w_20">');
                                    printf('<span>%dk</span>',round($data['sum_sale']/1000,0));
                                    print('</div>');
                                    print('</div>');
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile fixed_height_320 overflow_hidden">
                            <div class="x_title">
                                <h2>Posisi Stok Bahan</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="table table-bordered" style="width:100%">
                                    <tr>
                                        <th>SKU</th>
                                        <th>Nama Bahan</th>
                                        <th align="center">Stok</th>
                                    </tr>
                                    <?php
                                    foreach ($stokCard as $stok){
                                        if ($stok->QtyStok < 5) {
                                            print('<tr style="color: red">');
                                        }else{
                                            print('<tr style="color: green">');
                                        }
                                        printf('<td>%s</td>',$stok->Sku);
                                        printf('<td>%s</td>',$stok->ProdukNama);
                                        printf('<td align="right">%s</td>',$stok->QtyStok);
                                        print('</tr>');
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile fixed_height_320 overflow_hidden">
                            <div class="x_title">
                                <h2>Menu Favorite</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="" style="width:100%">
                                    <tr>
                                        <th style="width:37%;">
                                            <p>Top 5</p>
                                        </th>
                                        <th>
                                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                                <p class="">Menu</p>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                <p class="">Persentase</p>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <canvas class="canvasDoughnut" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                                        </td>
                                        <td>
                                            <table class="tile_info">
                                                <?php
                                                $acolor = array('blue','green','purple','aero','red');
                                                $i = 0;
                                                while ($data = $rsTop5Sale->FetchAssoc()){
                                                    print('<tr>');
                                                    printf('<td><p><i class="fa fa-square %s"></i> %s </p></td>',$acolor[$i],$data['sku']);
                                                    printf('<td>%s</td>',$data['sum_qty'].'%');
                                                    print('</tr>');
                                                    $i++;
                                                }
                                                ?>

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                &copy; 2017 <a href="http://eraditya.com">Erasystem Infotama Inc</a>.</strong> All rights reserved.
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="<?php print($helper->path("assets/vendors/jquery/dist/jquery.min.js"));?>"></script>
<!-- Bootstrap -->
<script src="<?php print($helper->path("assets/vendors/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
<!-- FastClick -->
<script src="<?php print($helper->path("assets/vendors/fastclick/lib/fastclick.js"));?>"></script>
<!-- NProgress -->
<script src="<?php print($helper->path("assets/vendors/nprogress/nprogress.js"));?>"></script>
<!-- Chart.js -->
<script src="<?php print($helper->path("assets/vendors/Chart.js/dist/Chart.min.js"));?>"></script>
<!-- gauge.js -->
<script src="<?php print($helper->path("assets/vendors/gauge.js/dist/gauge.min.js"));?>"></script>
<!-- bootstrap-progressbar -->
<script src="<?php print($helper->path("assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"));?>"></script>
<!-- iCheck -->
<script src="<?php print($helper->path("assets/vendors/iCheck/icheck.min.js"));?>"></script>
<!-- Skycons -->
<script src="<?php print($helper->path("assets/vendors/skycons/skycons.js"));?>"></script>
<!-- Flot -->
<script src="<?php print($helper->path("assets/vendors/Flot/jquery.flot.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/Flot/jquery.flot.pie.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/Flot/jquery.flot.time.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/Flot/jquery.flot.stack.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/Flot/jquery.flot.resize.js"));?>"></script>
<!-- Flot plugins -->
<script src="<?php print($helper->path("assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/flot-spline/js/jquery.flot.spline.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/flot.curvedlines/curvedLines.js"));?>"></script>
<!-- DateJS -->
<script src="<?php print($helper->path("assets/vendors/DateJS/build/date.js"));?>"></script>
<!-- JQVMap -->
<script src="<?php print($helper->path("assets/vendors/jqvmap/dist/jquery.vmap.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"));?>"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php print($helper->path("assets/vendors/moment/min/moment.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/bootstrap-daterangepicker/daterangepicker.js"));?>"></script>

<!-- Custom Theme Scripts -->
<script src="<?php print($helper->path("assets/build/js/custom.min.js"));?>"></script>
</body>
</html>
