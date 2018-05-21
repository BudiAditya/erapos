<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $stokins StokIn[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Daftar Penerimaan Bahan (Stok Masuk)</title>

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
    <!-- Select2 -->
    <link href="<?php print($helper->path("assets/vendors/select2/dist/css/select2.min.css"));?>" rel="stylesheet">
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
                                <h2>DAFTAR PENERIMAAN BAHAN (STOK IN)</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> INPUT PENERIMAAN BAHAN</button>
                                </p>
                                <table id="tablePos" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>Outlet</th>
                                        <th>No. StokIn</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Jumlah</th>
                                        <th>Terbayar</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
                                        <th align="center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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
<!-- Select2 -->
<script src="<?php print($helper->path("assets/vendors/select2/dist/js/select2.full.min.js"));?>"></script>
<script>
    $(document).ready( function ()
    {
        //tampilkan data table
        $('#tablePos').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("inventory/stokin/getJsonStokIn"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "outlet_kode" },
                { "data": "stokin_no" },
                { "data": "stokin_date" },
                { "data": "supp_name" },
                { "data": "fsub_total", "sClass": "rkanan" },
                { "data": "fpay_amt", "sClass": "rkanan" },
                { "data": "foutstanding", "sClass": "rkanan" },
                { "data": "dstokin_status" },
                { "data": "button", "sClass": "rcenter" }
            ]
        });
    });

    //proses tambah data
    $('#btAdd').click(function (e) {
        var oid = '<?php print($outletId);?>';
        var urx = "<?php print($helper->site_url("inventory/stokin/add"));?>";
        if (oid == 0){
            swal("Error!", '-PUSAT- tidak boleh melakukan proses ini!', "error");
        }else {
            location.href = urx;
        }
    });

    //proses edit
    $(document).on("click",".btsEdit",function(){
        var stokin_no = $(this).attr("stokin_no");
        var stokin_status = $(this).attr("stokin_status");
        var urz = "<?php print($helper->site_url("inventory/stokin/edit/"));?>"+stokin_no;
        if (stokin_status == 0){
            location.href = urz;
        }else{
            swal("Error!", 'Penerimaan sudah diproses!', "error");
        }
    });

    //proses view
    $(document).on("click",".btsView",function(){
        var stokin_no = $(this).attr("stokin_no");
        var stokin_status = $(this).attr("stokin_status");
        var urz = "<?php print($helper->site_url("inventory/stokin/view/"));?>"+stokin_no;
        location.href = urz;
    });

    //proses hapus data
    $(document).on( "click",".btsDelete", function() {
        var id_po = $(this).attr("id_po");
        var stokin_no = $(this).attr("stokin_no");
        var stokin_status = $(this).attr("stokin_status");
        if (stokin_status == 0) {
            swal({
                    title: "Hapus Data Penerimaan Bahan",
                    text: "Nomor: " + stokin_no + " ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Hapus",
                    closeOnConfirm: true
                },
                function () {
                    var dvalue = {stokin_no: stokin_no};
                    $.ajax(
                        {
                            url: "<?php print($helper->site_url("inventory/stokin/delete"));?>",
                            type: "POST",
                            data: dvalue,
                            success: function (data, textStatus, jqXHR) {
                                var data = jQuery.parseJSON(data);
                                if (data.result == 1) {
                                    $.notify('Berhasil hapus data pesanan!');
                                    var table = $('#tablePos').DataTable();
                                    table.ajax.reload(null, false);
                                } else {
                                    swal("Error", "Gagal hapus data pesanan, Error : " + data.error, "error");
                                }

                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal("Error!", textStatus, "error");
                            }
                        });
                });
        }else{
            swal("Error!", 'Penerimaan sudah diproses!', "error");
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
