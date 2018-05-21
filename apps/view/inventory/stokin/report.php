<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Laporan Pembelian </title>

    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php print($helper->path("assets/vendors/nprogress/nprogress.css"));?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php print($helper->path("assets/vendors/iCheck/skins/flat/green.css"));?>" rel="stylesheet">
    <!-- jConfirm -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/jquery-confirm.min.css"));?>">
    <!-- SweetAlert  style -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/sweetalert/sweetalert.css"));?>">
    <!-- responsive datatables -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css"));?>">
    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
    <!-- datepicker -->
    <link href="<?php print($helper->path("assets/plugins/datepicker/datepicker3.css"));?>" rel="stylesheet">
    <style>
        .rkanan { text-align: right; }
        .rcenter { text-align: center; }
    </style>
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
                                <h2>LAPORAN PEMBELIAN</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <div class="table-responsive">
                                  <form id="frm" action="<?php print($helper->site_url("inventory.stokin/report")); ?>" method="post">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>Outlet</th>
                                            <th>Dari Tanggal</th>
                                            <th>S/D Tanggal</th>
                                            <th>Jenis Laporan</th>
                                            <th>Output</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr>
                                            <td><select class="form-control" id="outletId" name="outletId" >
                                                    <option value="0"> All Outlet </option>
                                                    <?php
                                                    foreach ($outlets as $outlet) {
                                                        if ($outlet->Id == $outletId) {
                                                            printf('<option value="%d" selected="selected">%s</option>', $outlet->Id, $outlet->Kode);
                                                        } else {
                                                            printf('<option value="%d">%s</option>', $outlet->Id, $outlet->Kode);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="startDate" name="startDate" required placeholder="Tanggal" value="<?php print(date('Y-m-d',$startDate));?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="endDate" name="endDate" required placeholder="Tanggal" value="<?php print(date('Y-m-d',$endDate));?>">
                                                </div>
                                            </td>
                                            <td><select class="form-control" id="jnsLaporan" name="jnsLaporan" >
                                                    <option value="1" <?php print($jnsLaporan == 1 ? "selected='selected'" : "");?>>1 - Detail</option>
                                                    <option value="2" <?php print($jnsLaporan == 2 ? "selected='selected'" : "");?>>2 - Rekapitulasi</option>
                                                </select>
                                            </td>
                                            <td><select class="form-control" id="outPut" name="outPut" >
                                                    <option value="1" <?php print($outPut == 1 ? "selected='selected'" : "");?>>1 - HTML</option>
                                                    <option value="2" <?php print($outPut == 2 ? "selected='selected'" : "");?>>2 - Excel</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary btn-success">Proses</button>
                                            </td>
                                        </tr>
                                    </table>
                                  </form>
                              </div>
                              <div class="table-responsive">
                                  <table id="tableReport" class="table table-bordered table-condensed">
                                      <thead>
                                      <tr>
                                          <th>No.</th>
                                          <th>Outlet</th>
                                          <th>No. Transaksi</th>
                                          <th>Tanggal</th>
                                          <th>Supplier</th>
                                          <th>Jumlah</th>
                                          <th>Terbayar</th>
                                          <th>Outstanding</th>
                                          <th>Status</th>
                                      </tr>
                                      </thead>
                                      <?php
                                      $total = 0;
                                      $tpay = 0;
                                      $tout = 0;
                                      if ($reports != null) {
                                          print('<tbody>');
                                          /** @var $reports StokIn[] */
                                          $nmr = 0;
                                          foreach ($reports as $stokin) {
                                              if ($stokin->DStokInStatus <> 'VOID') {
                                                  $nmr++;
                                                  print('<tr>');
                                                  printf('<td>%d</td>', $nmr);
                                                  printf('<td>%s</td>', $stokin->OutletKode);
                                                  printf('<td>%s</td>', $stokin->StokInNo);
                                                  printf('<td>%s</td>', $stokin->StokInDate);
                                                  printf('<td>%s</td>', $stokin->SuppCode);
                                                  printf('<td align="right">%s</td>', number_format($stokin->SubTotal));
                                                  printf('<td align="right">%s</td>', number_format($stokin->PayAmt));
                                                  printf('<td align="right">%s</td>', number_format($stokin->SubTotal - $stokin->PayAmt));
                                                  printf('<td>%s</td>', $stokin->DStokInStatus);
                                                  print('</tr>');
                                                  $total+= $stokin->SubTotal;
                                                  $tpay+= $stokin->PayAmt;
                                              }
                                          }
                                          print('</tbody>');
                                      }
                                      $tout = $total - $tpay;
                                      ?>
                                      <tfoot>
                                      <th colspan="5" style="text-align: right">T o t a l </th>
                                      <th style="text-align: right"><?php print(number_format($total,0));?></th>
                                      <th style="text-align: right"><?php print(number_format($tpay,0));?></th>
                                      <th style="text-align: right"><?php print(number_format($tout,0));?></th>
                                      <th></th>
                                      </tfoot>
                                  </table>
                              </div>
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
<!-- NProgress -->
<script src="<?php print($helper->path("assets/vendors/nprogress/nprogress.js"));?>"></script>
<!-- iCheck -->
<script src="<?php print($helper->path("assets/vendors/iCheck/icheck.min.js"));?>"></script>
<!-- jConfirm -->
<script src="<?php print($helper->path("assets/cdn/js/jquery-confirm.min.js"));?>"></script>
<!-- SweetAlert -->
<script src="<?php print($helper->path("assets/plugins/sweetalert/sweetalert.min.js"));?>"></script>
<!-- Bootstrap-notify -->
<script src="<?php print($helper->path("assets/plugins/bootstrap-notify/bootstrap-notify.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/jquery.dataTables.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/dataTables.bootstrap.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"));?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php print($helper->path("assets/build/js/custom.min.js"));?>"></script>
<!-- date picker -->
<script src="<?php print($helper->path("assets/plugins/datepicker/bootstrap-datepicker.js"));?>"></script>
<script>
    $(document).ready( function () {
        //tampilkan data table
        $('#tableReport').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false,
            "ordering": false,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "bLengthChange": false
        });
    });

    $(function () {
        //Datemask dd/mm/yyyy
        //$('#tgl_mulai').inputmask('dd-mm-yyyy', {'placeholder': 'dd/mm/yyyy'})
        //Date picker
        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true
        });
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true
        });
    });

    //proses tambah data
    $('#btProses').click(function (e) {
        var oid = '<?php print($outletId);?>';
        var urx = "<?php print($helper->site_url("trx/sale/add"));?>";
        if (oid == 0){
            swal("Error!", '-PUSAT- tidak boleh melakukan penjualan langsung!', "error");
        }else {
            location.href = urx;
        }
    });

    //notify
    $.notifyDefaults({
        type: 'success',
        delay: 500
    });
</script>
</body>
</html>
