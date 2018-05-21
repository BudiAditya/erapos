<!DOCTYPE html>
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

    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
</head>

<body class="nav-sm">
<div class="container body">
    <div class="main_container">
        <!-- menu & navigation -->
        <?php include(VIEW . "main/menu.php"); ?>
        <!-- /top navigation -->

        <!-- page content -->
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
                            <p class="text-center">
                                <strong><?php print($textLabels);?></strong>
                            </p>
                            <div class="chart">
                                <!-- Trx Chart Canvas -->
                                <canvas id="trxChart" style="height: 200px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
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
<!-- Chart.js -->
<script src="<?php print($helper->path("assets/vendors/Chart.js/dist/Chart.min.js"));?>"></script>
<!-- bootstrap-progressbar -->
<script src="<?php print($helper->path("assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"));?>"></script>
<!-- iCheck -->
<script src="<?php print($helper->path("assets/vendors/iCheck/icheck.min.js"));?>"></script>
<!-- Skycons -->
<script src="<?php print($helper->path("assets/vendors/skycons/skycons.js"));?>"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php print($helper->path("assets/vendors/moment/min/moment.min.js"));?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php print($helper->path("assets/build/js/custom.min.js"));?>"></script>
<script>
    // -----------------------
    // - MONTHLY SALES CHART -
    // -----------------------

    // Get context with jQuery - using jQuery's .get() method.
    var trxChartCanvas = $('#trxChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var trxChart       = new Chart(trxChartCanvas);

    var trxChartData = {
        labels  : [<?php print($chartLabels);?>],
        datasets: [
            {
                label               : 'Revenues',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [<?php print($chartRevenues);?>]
            }
        ]
    };

    var trxChartOptions = {
        // Boolean - If we should show the scale at all
        showScale               : true,
        // Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines      : true,
        // String - Colour of the grid lines
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        // Number - Width of the grid lines
        scaleGridLineWidth      : 1,
        // Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        // Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines  : true,
        // Boolean - Whether the line is curved between points
        bezierCurve             : true,
        // Number - Tension of the bezier curve between points
        bezierCurveTension      : 0.3,
        // Boolean - Whether to show a dot for each point
        pointDot                : true,
        // Number - Radius of each point dot in pixels
        pointDotRadius          : 4,
        // Number - Pixel width of point dot stroke
        pointDotStrokeWidth     : 1,
        // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius : 20,
        // Boolean - Whether to show a stroke for datasets
        datasetStroke           : true,
        // Number - Pixel width of dataset stroke
        datasetStrokeWidth      : 2,
        // Boolean - Whether to fill the dataset with a color
        datasetFill             : false,
        // String - A legend template
        legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio     : true,
        // Boolean - whether to make the chart responsive to window resizing
        responsive              : true
    };

    // Create the line chart
    trxChart.Line(trxChartData, trxChartOptions);

    // ---------------------------
    // - END MONTHLY SALES CHART -
    // ---------------------------
</script>
</body>
</html>
