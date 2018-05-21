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

    <title>EraPOS | Laporan Penjualan </title>

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
                                <h2>LAPORAN PENJUALAN</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <div class="table-responsive">
                                  <form id="frm" action="<?php print($helper->site_url("trx.sale/report")); ?>" method="post">
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
                                                    <?php
                                                    if ($uOutletId == 1) {
                                                        print('<option value="0"> All Outlet </option>');
                                                    }
                                                    foreach ($outlets as $outlet) {
                                                        if ($outlet->Id > 1) {
                                                            if ($outlet->Id == $outletId) {
                                                                printf('<option value="%d" selected="selected">%s</option>', $outlet->Id, $outlet->Kode);
                                                            } else {
                                                                printf('<option value="%d">%s</option>', $outlet->Id, $outlet->Kode);
                                                            }
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
                                                    <option value="1" <?php print($jnsLaporan == 1 ? "selected='selected'" : "");?>> 1 - Detail Transaksi</option>
                                                    <option value="2" <?php print($jnsLaporan == 2 ? "selected='selected'" : "");?>> 2 - Rekap Per Tanggal</option>
                                                    <option value="3" <?php print($jnsLaporan == 3 ? "selected='selected'" : "");?>> 3 - Rekap Item Terjual</option>
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
                                  <?php
                                  if ($jnsLaporan == 1) {
                                      ?>
                                      <table id="tableReport" class="table table-bordered table-condensed">
                                          <thead>
                                          <tr>
                                              <th>No.</th>
                                              <?php
                                              if ($outletId == 0) {
                                                  print('<th>Outlet</th>');
                                              }
                                              ?>
                                              <th>No. Transaksi</th>
                                              <th>Tanggal</th>
                                              <th>Customer</th>
                                              <th> + Sub Total</th>
                                              <th> - Diskon</th>
                                              <th> + Pajak</th>
                                              <th> = Jumlah</th>
                                              <th>Status</th>
                                          </tr>
                                          </thead>
                                          <?php
                                          $total = 0;
                                          $tdiskon = 0;
                                          $tpajak = 0;
                                          $tsubtot = 0;
                                          if ($reports != null) {
                                              print('<tbody>');
                                              /** @var $reports Sale[] */
                                              $nmr = 0;
                                              foreach ($reports as $trxsale) {
                                                  if ($trxsale->DTrxStatus <> 'VOID') {
                                                      $nmr++;
                                                      print('<tr>');
                                                      printf('<td>%d</td>', $nmr);
                                                      if ($outletId == 0) {
                                                          printf('<td>%s</td>', $trxsale->OutletKode);
                                                      }
                                                      printf('<td>%s</td>', $trxsale->TrxNo);
                                                      printf('<td>%s</td>', $trxsale->TrxTime);
                                                      printf('<td>%s</td>', $trxsale->CustName);
                                                      printf('<td align="right">%s</td>', number_format($trxsale->SubTotal));
                                                      printf('<td align="right">%s</td>', number_format($trxsale->DiscAmt));
                                                      printf('<td align="right">%s</td>', number_format($trxsale->TaxAmt));
                                                      printf('<td align="right">%s</td>', number_format($trxsale->PayAmt));
                                                      printf('<td>%s</td>', $trxsale->DTrxStatus);
                                                      print('</tr>');
                                                      $tsubtot += $trxsale->SubTotal;
                                                      $tdiskon += $trxsale->DiscAmt;
                                                      $tpajak += $trxsale->TaxAmt;
                                                      $total += $trxsale->PayAmt;
                                                  }
                                              }
                                              print('</tbody>');
                                          }
                                          print('<tfoot>');
                                          if ($outletId == 0) {
                                              print('<th colspan="5" style="text-align: right">T o t a l </th>');
                                          } else {
                                              print('<th colspan="4" style="text-align: right">T o t a l </th>');
                                          }
                                          ?>
                                          <th style="text-align: right"><?php print(number_format($tsubtot, 0)); ?></th>
                                          <th style="text-align: right"><?php print(number_format($tdiskon, 0)); ?></th>
                                          <th style="text-align: right"><?php print(number_format($tpajak, 0)); ?></th>
                                          <th style="text-align: right"><?php print(number_format($total, 0)); ?></th>
                                          <th></th>
                                          </tfoot>
                                      </table>
                                      <?php
                                  }elseif ($jnsLaporan == 2){
                                  ?>
                                  <table id="tableReport" class="table table-bordered table-condensed">
                                      <thead>
                                      <tr>
                                          <th>No.</th>
                                          <?php
                                          if ($outletId == 0) {
                                              print('<th>Outlet</th>');
                                          }
                                          ?>
                                          <th>Tanggal</th>
                                          <th> + Sub Total</th>
                                          <th> - Diskon</th>
                                          <th> + Pajak</th>
                                          <th> = Jumlah</th>
                                      </tr>
                                      </thead>
                                      <?php
                                      if ($reports != null) {
                                          print('<tbody>');
                                          $nmr = 0;
                                          $total = 0;
                                          $tdiskon = 0;
                                          $tpajak = 0;
                                          $tsubtot = 0;
                                          while ($data = $reports->FetchAssoc()) {
                                              $nmr++;
                                              print('<tr>');
                                              printf('<td>%d</td>', $nmr);
                                              if ($outletId == 1) {
                                                  printf('<td>%s</td>', $data['outlet_kode']);
                                              }
                                              printf('<td>%s</td>', $data['trx_date']);
                                              printf('<td align="right">%s</td>', number_format($data['sub_total']));
                                              printf('<td align="right">%s</td>', number_format($data['diskon']));
                                              printf('<td align="right">%s</td>', number_format($data['pajak']));
                                              printf('<td align="right">%s</td>', number_format($data['jumlah']));
                                              print('</tr>');
                                              $total += $data['jumlah'];
                                              $tsubtot += $data['sub_total'];
                                              $tdiskon += $data['diskon'];
                                              $tpajak += $data['pajak'];
                                          }
                                          print('</tbody>');
                                      }
                                      print('<tfoot>');
                                      if ($outletId == 0) {
                                          print('<th colspan="3" style="text-align: right">T o t a l </th>');
                                      } else {
                                          print('<th colspan="2" style="text-align: right">T o t a l </th>');
                                      }
                                      ?>
                                      <th style="text-align: right"><?php print(number_format($tsubtot, 0)); ?></th>
                                      <th style="text-align: right"><?php print(number_format($tdiskon, 0)); ?></th>
                                      <th style="text-align: right"><?php print(number_format($tpajak, 0)); ?></th>
                                      <th style="text-align: right"><?php print(number_format($total, 0)); ?></th>
                                      </tfoot>
                                  </table>
                                      <?php
                                  }else{
                                    ?>
                                  <table id="tableReport" class="table table-bordered table-condensed">
                                      <thead>
                                      <tr>
                                          <th>No.</th>
                                          <th>SKU</th>
                                          <th>Nama Produk</th>
                                          <th>QTY</th>
                                          <th>Satuan</th>
                                          <th>Nilai Penjualan</th>
                                      </tr>
                                      </thead>
                                      <?php
                                      $total = 0;
                                      if ($reports != null) {
                                          print('<tbody>');
                                          $nmr = 0;
                                          while ($data = $reports->FetchAssoc()) {
                                              $nmr++;
                                              print('<tr>');
                                              printf('<td>%d</td>', $nmr);
                                              printf('<td>%s</td>', $data['sku']);
                                              printf('<td>%s</td>', $data['nama']);
                                              printf('<td align="right">%s</td>', number_format($data['sum_qty']));
                                              printf('<td>%s</td>', $data['satuan']);
                                              printf('<td align="right">%s</td>', number_format($data['sum_jumlah']));
                                              print('</tr>');
                                              $total += $data['sum_jumlah'];
                                          }
                                          print('</tbody>');
                                      }
                                      print('<tfoot>');
                                      print('<th colspan="5" style="text-align: right">Total (* Sebelum diskon dan pajak) </th>');
                                      ?>
                                      <th style="text-align: right"><?php print(number_format($total, 0)); ?></th>
                                      </tfoot>
                                  </table>
                                  <?php
                                  }
                                  ?>
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
