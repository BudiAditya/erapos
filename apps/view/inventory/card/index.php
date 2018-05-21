<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $cards Card[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Daftar Stok </title>

    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php print($helper->path("assets/vendors/iCheck/skins/flat/green.css"));?>" rel="stylesheet">
    <!-- responsive datatables -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css"));?>">
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
            <div class="">
                <div class="clearfix"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>KARTU STOK PRODUK & BAHAN</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table id="tableStok" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th align="center">No.</th>
                                        <!--<th>Outlet</th>-->
                                        <th>SKU</th>
                                        <th>Nama Produk</th>
                                        <th>Satuan</th>
                                        <th align="right">Awal</th>
                                        <th align="right">Masuk</th>
                                        <th align="right">Keluar</th>
                                        <th align="right">Koreksi</th>
                                        <th align="right">Stok</th>
                                        <th align="center">Detail</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $nmr = 1;
                                    foreach ($cards as $card){
                                        print('<tr>');
                                        printf('<td align="center">%d</td>',$nmr);
                                        //printf('<td>%s</td>',$card->OutletKode);
                                        printf('<td>%s</td>',$card->Sku);
                                        printf('<td>%s</td>',$card->ProdukNama);
                                        printf('<td>%s</td>',$card->ProdukSatuan);
                                        printf('<td align="right">%s</td>',number_format($card->QtyAwal,0));
                                        printf('<td align="right">%s</td>',number_format($card->QtyIn,0));
                                        printf('<td align="right">%s</td>',number_format($card->QtyOut,0));
                                        printf('<td align="right">%s</td>',number_format($card->QtyKoreksi,0));
                                        printf('<td align="right">%s</td>',number_format($card->QtyStok,0));
                                        printf('<td align="center"><button type="button" kd_sku="%s" class="btn btn-primary btn-xs btcView" ><i class="fa fa-file-text-o"></i></button></td>',$card->Sku);
                                        print('</tr>');
                                        $nmr++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
<!-- SlimScroll -->
<script src="<?php print($helper->path("assets/plugins/slimScroll/jquery.slimscroll.min.js"));?>"</script>
<!-- FastClick -->
<script src="<?php print($helper->path("assets/vendors/fastclick/lib/fastclick.js"));?>"></script>

<script src="<?php print($helper->path("assets/plugins/datatables/jquery.dataTables.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/dataTables.bootstrap.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"));?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php print($helper->path("assets/build/js/custom.min.js"));?>"></script>
<script>
    $(document).ready( function ()
    {
        //tampilkan data table
        $('#tableStok').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": true,
            "pageLength": 10
        });
    });

    //proses view
    $(document).on("click",".btcView",function(){
        var sku = $(this).attr("kd_sku");
        var urz = "<?php print($helper->site_url("inventory/card/view/"));?>"+sku;
        location.href = urz;
    });
</script>
</body>
</html>
