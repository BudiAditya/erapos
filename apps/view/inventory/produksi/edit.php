<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $produks Produk[] */
/** @var $produksi Produksi */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EraPOS | Proses Produksi </title>
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
                            <h2><b>PROSES PRODUKSI</b></h2>
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
                                        <label for="txtProdDate" class="col-sm-1 control-label">Tanggal</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="txtProdDate" placeholder="Tanggal" name="txtProdDate" value="<?php print($produksi->ProdDate);?>" style="text-align: left;font-weight: bold">
                                        </div>
                                        <label for="txtProdNo" class="col-sm-2 control-label">No. Produksi</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="txtProdNo" placeholder="No. PO" name="txtProdNo" value="<?php print($produksi->ProdNo);?>" readonly style="text-align: left;font-weight: bold">
                                        </div>
                                        <label for="txtNotes" class="col-sm-1 control-label">Notes</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="txtNotes" placeholder="Catatan" name="txtNotes" value="<?php print($produksi->Notes);?>" style="text-align: left;">
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="cboProdStatus" name="cboProdStatus" disabled>
                                                <option value="0" <?php print($produksi->ProdStatus == 0 ? 'selected="selected"' : '');?>> DRAFT</option>
                                                <option value="1" <?php print($produksi->ProdStatus == 1 ? 'selected="selected"' : '');?>> POSTED</option>
                                                <option value="3" <?php print($produksi->ProdStatus == 3 ? 'selected="selected"' : '');?>> VOID</option>
                                            </select>
                                            <input type="hidden" id="txtProdStatus" name="txtProdStatus" value="<?php print($produksi->ProdStatus);?>">
                                        </div>
                                    </div>
                                </form>
                                <div class="separator"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-sm-6">
                                    <h4><u>BAHAN BAKU</u></h4>
                                </div>
                                <div class="col-sm-6 rkanan">
                                    <button type="button" class="btn btn-primary btn-sm" id="btdAdd" name="btdAdd"><i class="fa fa-plus"></i> BAHAN</button>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="table table-hover table-striped" id="tableBahan" width="100%">
                                            <thead>
                                            <tr>
                                                <th>QTY</th>
                                                <th>Nama Bahan</th>
                                                <th>SKU</th>
                                                <th>Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4><u>HASIL PRODUKSI</u></h4>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <table class="table table-hover table-striped" id="tableHasil" width="100%">
                                            <thead>
                                            <tr>
                                                <th>QTY</th>
                                                <th>Nama Produk</th>
                                                <th>SKU</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="separator"></div>
                                <?php
                                if ($userLevel > 1){
                                    print('<button type="button" class="btn btn-primary" id="btProses"><i class="fa fa-save"></i> PROSES</button>');
                                    print('<button type="button" class="btn btn-warning" id="btVoid"><i class="fa fa-remove"></i> VOID</button>');
                                }else{
                                    print('<button type="button" class="btn btn-primary" id="btProses" disabled><i class="fa fa-save"></i> PROSES</button>');
                                    print('<button type="button" class="btn btn-warning" id="btVoid" disabled><i class="fa fa-remove"></i> VOID</button>');
                                }
                                ?>
                                <button type="button" class="btn btn-default" id="btClose"><i class="fa fa-remove"></i> TUTUP</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- /page content -->
        <!-- entry detail modal -->
        <div id="modalEntryDetail" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">PILIH BAHAN YANG DIGUNAKAN</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="txtSku" class="col-md-3  control-label">NAMA BAHAN</label>
                                <div class="col-md-9">
                                    <select class="form-control" id = "txtSku" name = "txtSku">
                                        <option value="0"> Pilih Bahan </option>
                                    <?php
                                        foreach ($produks as $produk){
                                            printf('<option value="%s">%s - %s',$produk->Sku,$produk->Sku,$produk->Nama);
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtQty" class="col-md-3  control-label">QTY</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="txtQty" name="txtQty" placeholder="QTY" value="0" style="text-align: right" required>
                                </div>
                            </div>
                            <!--</form>-->
                        </div>
                        <!--modal footer-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btSvDetail" class="btn bt-sm btn-primary"><i class="fa fa-save"></i> SAVE</button>
                        <button type="button" class="btn btn-default btn-sm" id="btCancel"><i class="fa fa-close"></i> Cancel</button>
                    </div>
                    <!--modal-content-->
                </div>
                <!--modal-dialog modal-lg-->
            </div>
        </div>
        <!-- /entry detail modal -->
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
        $('#txtProdDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true
        })

        //tampilkan data bahan produksi
        $('#tableBahan').DataTable({
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: false,
            autoWidth: true,
            pageLength: 10,
            ajax: {
                "url": "<?php print($helper->site_url("inventory/produksi/getJsonBahan"));?>",
                "type": "POST",
                "data": function ( d ) {d.prod_no = document.getElementById("txtProdNo").value}
            },
            columns: [
                { "data": "qty", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "sku" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "button", "sClass": "rcenter" }
            ]
        });

        //tampilkan data hasil produksi
        $('#tableHasil').DataTable({
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: false,
            autoWidth: true,
            pageLength: 10,
            ajax: {
                "url": "<?php print($helper->site_url("inventory/produksi/getJsonHasil"));?>",
                "type": "POST",
                "data": function ( d ) {d.prod_no = document.getElementById("txtProdNo").value}
            },
            columns: [
                { "data": "qty", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "sku" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "sub_total", "sClass": "rkanan" }
            ]
        });


        $("#btdAdd").click(function (e) {
            //proses entry detail
            $("#txtSku").val('0');
            $("#txtQty").val('0');
            $("#modalEntryDetail").modal('show');
        });

        //simpan detail
        $(document).on("click","#btSvDetail",function(){
            var prod_date = $("#txtProdDate").val();
            var prod_no = $("#txtProdNo").val();
            var notes = $("#txtNotes").val();
            var sku = $('#txtSku').val();
            var qty = $('#txtQty').val();
            var urm = "<?php print($helper->site_url("inventory/produksi/addmaster"));?>";
            var urd = "<?php print($helper->site_url("inventory/produksi/addetail"));?>";
            var oke = 1;
            if (prod_date == null || prod_date == ''){
                alert('Tanggal Produksi belum diisi!');
                $('#txtProdDate').focus();
                oke = 0;
            }
            if (sku == 0 || sku == ''){
                alert('Bahan yang dipesan belum diisi!');
                $('#txtSku').focus();
                oke = 0;
            }
            if (qty < 1  || qty == ''){
                alert('QTY Order belum diisi!');
                $('#txtQty').focus();
                oke = 0;
            }
            if (oke == 1) {
                if (prod_no == 0) {
                    $.ajax({
                        type: "POST",
                        url: urm,
                        data: $("#frm").serialize(), // serializes the form's elements.
                        success: function (data) {
                            //alert(data); // show response from the php script.
                            $("#txtProdNo").val(data);
                            prod_no = data;
                            var datx = {prod_no: prod_no, sku: sku, qty: qty};
                            $.ajax({
                                type: "POST",
                                url: urd,
                                data: datx,
                                success: function (dta) {
                                    //alert(dta); // show response from the php script.
                                    var table1 = $('#tableBahan').DataTable();
                                    var table2 = $('#tableHasil').DataTable();
                                    var stotal = 0;
                                    var urs = "<?php print($helper->site_url("inventory/produksi/getSubTotal/"));?>" + prod_no;
                                    table1.ajax.reload(null, false);
                                    table2.ajax.reload(null, false);
                                    $.get(urs, function (data) {
                                        stotal = Intl.NumberFormat().format(data);
                                        $("#txtSubTotal").val(stotal);
                                        $("#txtSku").val('0');
                                        $("#txtQty").val('0');
                                        $("#modalEntryDetail").modal('hide');
                                    });
                                }
                            });
                        }
                    });
                } else {
                    var data = {prod_no: prod_no, sku: sku, qty: qty};
                    $.ajax({
                        type: "POST",
                        url: urd,
                        data: data,
                        success: function (dtz) {
                            //alert(dtz); // show response from the php script.
                            var table1 = $('#tableBahan').DataTable();
                            var table2 = $('#tableHasil').DataTable();
                            var stotal = 0;
                            var urs = "<?php print($helper->site_url("inventory/produksi/getSubTotal/"));?>" + prod_no;
                            table1.ajax.reload(null, false);
                            table2.ajax.reload(null, false);
                            $.get(urs, function (data) {
                                stotal = Intl.NumberFormat().format(data);
                                $("#txtSubTotal").val(stotal);
                                $("#txtSku").val('0');
                                $("#txtQty").val('0');
                                $("#modalEntryDetail").modal('hide');
                            });
                        }
                    });
                }
            }
        });

        //batalkan proses
        $(document).on("click","#btCancel",function(){
            $("#txtSku").val('0');
            $("#txtQty").val('0');
            $("#modalEntryDetail").modal('hide');
        });

        //hapus detail bahan
        $(document).on("click",".btdRemove",function(){
            var prod_no = $("#txtProdNo").val();
            var pid = $(this).attr("id_detail");
            var psku = $(this).attr("sku");
            var pnama = $(this).attr("nama");
            var urd = "<?php print($helper->site_url("inventory/produksi/deldetail"));?>";
            swal({
                    title: "Hapus Data Bahan Produksi",
                    text: "SKU: "+psku+" - "+pnama+" ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Hapus",
                    closeOnConfirm: true },
                function(){
                    var data = {id: pid};
                    $.ajax({
                        type: "POST",
                        url: urd,
                        data: data,
                        success: function(dtz, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(dtz);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data bahan produksi!');
                                var table1 = $('#tableBahan').DataTable();
                                var table2 = $('#tableHasil').DataTable();
                                var stotal = 0;
                                table1.ajax.reload(null, false);
                                table2.ajax.reload(null, false);
                                var urs = "<?php print($helper->site_url("inventory/produksi/getSubTotal/"));?>" + prod_no;
                                $.get(urs, function (data) {
                                    stotal = Intl.NumberFormat().format(data);
                                    $("#txtSubTotal").val(stotal);
                                });
                            }else{
                                swal("Error","Gagal hapus detail produksi, Error : "+data.error,"error");
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            swal("Error!", textStatus, "error");
                        }
                    });
                });
        });

        //proses produksi
        $("#btProses").click(function (e) {
            //proses void disini
            var prod_no = $("#txtProdNo").val();
            var prod_status = $("#txtProdStatus").val();
            if (prod_status == 0) {
                swal({
                        title: "Proses Approval Produksi",
                        text: "Nomor: " + prod_no + " ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Proses",
                        closeOnConfirm: true
                    },
                    function () {
                        var dvalue = {prod_no: prod_no};
                        $.ajax(
                            {
                                url: "<?php print($helper->site_url("inventory/produksi/proses"));?>",
                                type: "POST",
                                data: dvalue,
                                success: function (data, textStatus, jqXHR) {
                                    var data = jQuery.parseJSON(data);
                                    if (data.result == 1) {
                                        $.notify('Proses data produksi berhasil!');
                                        var table1 = $('#tableBahan').DataTable();
                                        var table2 = $('#tableHasil').DataTable();
                                        table1.ajax.reload(null, false);
                                        table2.ajax.reload(null, false);
                                        $("#cboProdStatus").val(1);
                                        $("#txtProdStatus").val(1);
                                    } else {
                                        swal("Error", "Gagal proses data produksi, Error : " + data.error, "error");
                                    }

                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal("Error!", textStatus, "error");
                                }
                            });
                    });
            }else{
                swal("Error!", 'Produksi sudah diproses!', "error");
            }
        });

        //proses batal produksi
        $("#btVoid").click(function (e) {
            //proses void disini
            var prod_no = $("#txtProdNo").val();
            var prod_status = $("#txtProdStatus").val();
            if (prod_status == 0) {
                swal({
                        title: "Batalkan Produksi",
                        text: "Nomor: " + prod_no + " ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Hapus",
                        closeOnConfirm: true
                    },
                    function () {
                        var dvalue = {prod_no: prod_no};
                        $.ajax(
                            {
                                url: "<?php print($helper->site_url("inventory/produksi/delete"));?>",
                                type: "POST",
                                data: dvalue,
                                success: function (data, textStatus, jqXHR) {
                                    var data = jQuery.parseJSON(data);
                                    if (data.result == 1) {
                                        $.notify('Berhasil hapus data produksi!');
                                        var urz = "<?php print($helper->site_url("inventory/produksi"));?>";
                                        location.href = urz;
                                    } else {
                                        swal("Error", "Gagal hapus data produksi, Error : " + data.error, "error");
                                    }

                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    swal("Error!", textStatus, "error");
                                }
                            });
                    });
            }else{
                swal("Error!", 'Produksi sudah diproses!', "error");
            }
        });

        $("#btClose").click(function (e) {
            //proses tutup form disini
            var url = "<?php print($helper->site_url("inventory/produksi"));?>";
            location.href = url;
        });
    });
    
</script>
</body>
</html>
