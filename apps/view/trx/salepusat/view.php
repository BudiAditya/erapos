<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $produks Produk[] */
/** @var $customers Customer[] */
/** @var $salepusat SalePusat */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EraPOS | Penjualan Bahan </title>
    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php print($helper->path("assets/vendors/nprogress/nprogress.css"));?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php print($helper->path("assets/vendors/iCheck/skins/flat/green.css"));?>" rel="stylesheet">
    <!-- SweetAlert  style -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/sweetalert/sweetalert.css"));?>">
    <!-- responsive datatables -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css"));?>">
    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
    <!-- Select2 -->
    <link href="<?php print($helper->path("assets/vendors/select2/dist/css/select2.min.css"));?>" rel="stylesheet">
    <!-- jConfirm -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/jquery-confirm.min.css"));?>">
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
                                <h2><b>PENJUALAN BAHAN PUSAT</b></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="pad" id="infopanel"></div>
                                <div class="col-md-12">
                                    <form id="frm" class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label for="txtByOutletId" class="col-md-2 control-label">Outlet / Customer</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="txtByOutletId" name="txtByOutletId" required>
                                                    <?php
                                                    foreach ($outlets as $outlet) {
                                                        if ($outlet->Id == $salepusat->ByOutletId){
                                                            printf('<option value="%d" selected="selected">%s - %s</option>', $outlet->Id, $outlet->Kode, $outlet->OutletName);
                                                        }else {
                                                            printf('<option value="%d">%s - %s</option>', $outlet->Id, $outlet->Kode, $outlet->OutletName);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" id="txtTrxStatus" name="txtTrxStatus" value="<?php print($salepusat->TrxStatus);?>">
                                                <input type="hidden" id="txtOutletId" name="txtOutletId" value="<?php print($salepusat->OutletId);?>">
                                                <input type="hidden" id="usrOutletId" name="usrOutletId" value="<?php print($outletId);?>">
                                            </div>
                                            <label for="txtTrxDate" class="col-sm-2 control-label">Tgl Penjualan</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtTrxDate" placeholder="Tanggal" name="txtTrxDate" value="<?php print($salepusat->TrxDate);?>" style="text-align: center;font-weight: bold">
                                            </div>
                                            <label for="txtTrxNo" class="col-sm-2 control-label">No. Trx</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtTrxNo" placeholder="No.Trx" name="txtTrxNo" value="<?php print($salepusat->TrxNo);?>" readonly style="text-align: center;font-weight: bold">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group" id="divDetail">
                                            <div class="col-sm-12">
                                            <table class="table table-striped table-hover table-bordered" id="tableDetail" width="100%">
                                                <thead>
                                                <tr>
                                                    <th align="center">QTY</th>
                                                    <th>NAMA BAHAN</th>
                                                    <th>SKU</th>
                                                    <th align="right">HARGA</th>
                                                    <th align="right">JUMLAH</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <label for="cboTrxStatus" class="col-md-2 control-label">Status Penjualan</label>
                                            <div class="col-md-4">
                                                <select class="form-control" id="cboTrxStatus" name="cboTrxStatus" disabled>
                                                    <option value="0" <?php print($salepusat->TrxStatus == 0 ? 'selected="selected"' : '');?>> 0 - OPEN </option>
                                                    <option value="1" <?php print($salepusat->TrxStatus == 1 ? 'selected="selected"' : '');?>> 1 - CLOSE </option>
                                                    <option value="2" <?php print($salepusat->TrxStatus == 2 ? 'selected="selected"' : '');?>> 2 - PAID </option>
                                                    <option value="3" <?php print($salepusat->TrxStatus == 3 ? 'selected="selected"' : '');?>> 3 - VOID </option>
                                                </select>
                                            </div>
                                            <label for="txtSubTotal" class="col-md-4 control-label" style="font-weight: bold">T O T A L </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control num" id="txtSubTotal" placeholder="Sub Total" name="SubTotal" value="<?php print(number_format($salepusat->SubTotal)) ?>" readonly style="text-align: right; font-weight: bold">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="col-sm-12">
                                            <?php
                                            if ($outletId == $salepusat->OutletId){
                                               print('<button type="button" class="btn btn-primary" id="btProses"><i class="fa fa-save"></i> PROSES</button>');
                                            }else{
                                               print('<button type="button" class="btn btn-primary" id="btProses" disabled><i class="fa fa-save"></i> PROSES</button>');
                                            }
                                            ?>
                                            <button type="button" class="btn btn-warning" id="btVoid"><i class="fa fa-remove"></i> VOID</button>
                                            <button type="button" class="btn btn-default" id="btClose"><i class="fa fa-remove"></i> TUTUP</button>
                                        </div>
                                    </form>
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
<!-- SweetAlert -->
<script src="<?php print($helper->path("assets/plugins/sweetalert/sweetalert.min.js"));?>"></script>
<!-- Bootstrap-notify -->
<script src="<?php print($helper->path("assets/plugins/bootstrap-notify/bootstrap-notify.min.js"));?>"></script>
<!-- Datatables -->
<script src="<?php print($helper->path("assets/plugins/datatables/jquery.dataTables.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/dataTables.bootstrap.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"));?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php print($helper->path("assets/build/js/custom.min.js"));?>"></script>
<!-- jConfirm -->
<script src="<?php print($helper->path("assets/cdn/js/jquery-confirm.min.js"));?>"></script>
<!-- autonumeric -->
<script src="<?php print($helper->path("public/js/auto-numeric.js"));?>"></script>
<!-- datepicker -->
<script src="<?php print($helper->path("assets/plugins/datepicker/bootstrap-datepicker.js"));?>"></script>
<script>
    $(document).ready( function ()
    {
        //Date picker
        $('#txtTrxDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true
        })

        //tampilkan data detail
        $('#tableDetail').DataTable({
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: false,
            autoWidth: true,
            pageLength: 10,
            ajax: {
                "url": "<?php print($helper->site_url("trx/salepusat/getJsonSalePusatDetail"));?>",
                "type": "POST",
                "data": function ( d ) {d.trx_no = document.getElementById("txtTrxNo").value}
            },
            columns: [
                { "data": "qty_kirim", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "sku" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "sub_total", "sClass": "rkanan" }
            ]
        });

        //proses approval penjualan
        $("#btProses").click(function (e) {
            //proses void disini
            var trx_no = $("#txtTrxNo").val();
            var trx_status = $("#txtTrxStatus").val();
            if (trx_status == 0) {
                swal({
                        title: "Proses Approval Penjualan",
                        text: "Nomor: " + trx_no + " ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Proses",
                        closeOnConfirm: true
                    },
                    function () {
                        var dvalue = {trx_no: trx_no};
                        $.ajax(
                            {
                                url: "<?php print($helper->site_url("trx/salepusat/proses"));?>",
                                type: "POST",
                                data: dvalue,
                                success: function (data, textStatus, jqXHR) {
                                    var data = jQuery.parseJSON(data);
                                    if (data.result == 1) {
                                        $.notify('Berhasil proses data penjualan!');
                                        var urz = "<?php print($helper->site_url("trx/salepusat"));?>";
                                        location.href = urz;
                                    } else {
                                        swal("Error", "Gagal proses data penjualan, Error : " + data.error, "error");
                                    }

                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal("Error!", textStatus, "error");
                                }
                            });
                    });
            }else{
                swal("Error!", 'Penjualan sudah diproses!', "error");
            }
        });
        
        //proses void
        $("#btVoid").click(function (e) {
            //proses void disini
            var trx_no = $("#txtTrxNo").val();
            var trx_status = $("#txtTrxStatus").val();
            if (trx_status == 0) {
                swal({
                        title: "Batalkan Penjualan Bahan",
                        text: "Nomor: " + trx_no + " ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Hapus",
                        closeOnConfirm: true
                    },
                    function () {
                        var dvalue = {trx_no: trx_no};
                        $.ajax(
                            {
                                url: "<?php print($helper->site_url("trx/salepusat/delete"));?>",
                                type: "POST",
                                data: dvalue,
                                success: function (data, textStatus, jqXHR) {
                                    var data = jQuery.parseJSON(data);
                                    if (data.result == 1) {
                                        $.notify('Berhasil hapus data penjualan!');
                                        var urz = "<?php print($helper->site_url("trx/salepusat"));?>";
                                        location.href = urz;
                                    } else {
                                        swal("Error", "Gagal hapus data penjualan, Error : " + data.error, "error");
                                    }

                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal("Error!", textStatus, "error");
                                }
                            });
                    });
            }else{
                swal("Error!", 'Penjualan sudah diproses!', "error");
            }
        });

        $("#btClose").click(function (e) {
            //proses tutup form disini
            var stotal = $("#txtSubTotal").val();
            var url = "<?php print($helper->site_url("trx/salepusat"));?>";
            location.href = url;
        });
    });
</script>
</body>
</html>
