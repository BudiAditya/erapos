<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $produks Produk[] */
/** @var $suppliers Supplier[] */
/** @var $stokin StokIn */
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
                                            <label for="txtSuppCode" class="col-md-2 control-label">Nama Supplier</label>
                                            <div class="col-md-2">
                                                <select class="form-control" id="txtSuppCode" name="txtSuppCode" required>
                                                    <?php
                                                    foreach ($suppliers as $supp) {
                                                        if ($supp->Kode == $stokin->SuppCode){
                                                            printf('<option value="%s" selected="selected">%s - %s</option>', $supp->Kode, $supp->Kode, $supp->Nama);
                                                        }else {
                                                            printf('<option value="%s">%s - %s</option>', $supp->Kode, $supp->Kode, $supp->Nama);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" id="txtStokInStatus" name="txtStokInStatus" value="<?php print($stokin->StokInStatus);?>">
                                                <input type="hidden" id="usrOutletId" name="usrOutletId" value="<?php print($outletId);?>">
                                            </div>
                                            <label for="txtStokInDate" class="col-sm-2 control-label">Tgl Penjualan</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtStokInDate" placeholder="Tanggal" name="txtStokInDate" value="<?php print($stokin->StokInDate);?>" style="text-align: center;font-weight: bold">
                                            </div>
                                            <label for="txtStokInNo" class="col-sm-2 control-label">No. PO</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtStokInNo" placeholder="No. PO" name="txtStokInNo" value="<?php print($stokin->StokInNo);?>" readonly style="text-align: center;font-weight: bold">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group" id="divDetail">
                                            <div class="col-sm-12">
                                                <p>
                                                    <button type="button" class="btn btn-primary btn-sm" id="btdAdd" name="btdAdd"><i class="fa fa-plus"></i> TAMBAH BAHAN</button>
                                                </p>
                                            <table class="table table-striped table-hover table-bordered" id="tableDetail" width="100%">
                                                <thead>
                                                <tr>
                                                    <th align="center">QTY</th>
                                                    <th>NAMA BAHAN</th>
                                                    <th>SKU</th>
                                                    <th align="right">HARGA</th>
                                                    <th align="right">JUMLAH</th>
                                                    <th align="right">AKSI</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <label for="cboStokInStatus" class="col-md-2 control-label">Status Penjualan</label>
                                            <div class="col-md-4">
                                                <select class="form-control" id="cboStokInStatus" name="cboStokInStatus" disabled>
                                                    <option value="0" <?php print($stokin->StokInStatus == 0 ? 'selected="selected"' : '');?>> 0 - OPEN </option>
                                                    <option value="1" <?php print($stokin->StokInStatus == 1 ? 'selected="selected"' : '');?>> 1 - CLOSE </option>
                                                    <option value="2" <?php print($stokin->StokInStatus == 2 ? 'selected="selected"' : '');?>> 2 - PAID </option>
                                                    <option value="3" <?php print($stokin->StokInStatus == 3 ? 'selected="selected"' : '');?>> 3 - VOID </option>
                                                </select>
                                            </div>
                                            <label for="txtSubTotal" class="col-md-4 control-label" style="font-weight: bold">T O T A L </label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control num" id="txtSubTotal" placeholder="Sub Total" name="SubTotal" value="<?php print(number_format($stokin->SubTotal)) ?>" readonly style="text-align: right; font-weight: bold">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="col-sm-12">
                                            <?php
                                            if ($outletId == 0){
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
        <!-- entry detail modal -->
        <div id="modalEntryDetail" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">PILIH BAHAN YANG DIJUAL</div></h4>
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
        $('#txtStokInDate').datepicker({
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
                "url": "<?php print($helper->site_url("trx/salepusat/getJsonStokInDetail"));?>",
                "type": "POST",
                "data": function ( d ) {d.stokin_no = document.getElementById("txtStokInNo").value}
            },
            columns: [
                { "data": "qty_terima", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "sku" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "sub_total", "sClass": "rkanan" },
                { "data": "button", "sClass": "rcenter" }
            ]
        });


        $("#btdAdd").click(function (e) {
            //proses entry detail
            $("#txtSku").val('0');
            $("#txtQty").val('0');
            $("#modalEntryDetail").modal('show');
        });

        $("#btVoid").click(function (e) {
            //proses void disini
            var stokin_no = $("#txtStokInNo").val();
            var po_status = $("#txtStokInStatus").val();
            if (po_status == 0) {
                swal({
                        title: "Batalkan Penjualan Bahan",
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
    //hapus detail
    $(document).on("click",".btdRemove",function(){
        var stokin_no = $("#txtStokInNo").val();
        var pid = $(this).attr("id_detail");
        var psku = $(this).attr("sku");
        var pnama = $(this).attr("nama");
        var urd = "<?php print($helper->site_url("trx/salepusat/deldetail"));?>";
        swal({
                title: "Hapus Data Penjualan",
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
                            $.notify('Berhasil hapus data penjualan!');
                            var table = $('#tableDetail').DataTable();
                            var stotal = 0;
                            table.ajax.reload(null, false);
                            var urs = "<?php print($helper->site_url("trx/salepusat/getSubTotal/"));?>" + stokin_no;
                            $.get(urs, function (data) {
                                stotal = Intl.NumberFormat().format(data);
                                $("#txtSubTotal").val(stotal);
                            });
                        }else{
                            swal("Error","Gagal hapus detail penjualan, Error : "+data.error,"error");
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        swal("Error!", textStatus, "error");
                    }
                });
            });
    });
    
    //simpan detail
    $(document).on("click","#btSvDetail",function(){
        var po_date = $("#txtStokInDate").val();
        var stokin_no = $("#txtStokInNo").val();
        var sku = $('#txtSku').val();
        var qty = $('#txtQty').val();
        var urm = "<?php print($helper->site_url("trx/salepusat/addmaster"));?>";
        var urd = "<?php print($helper->site_url("trx/salepusat/addetail"));?>";
        var oke = 1;
        if (po_date == null || po_date == ''){
            alert('Tanggal PO belum diisi!');
            $('#txtStokInDate').focus();
            oke = 0;
        }
        if (sku == 0 || sku == ''){
            alert('Bahan yang dipesan belum diisi!');
            $('#txtSku').focus();
            oke = 0;
        }
        if (qty == 0 || qty == ''){
            alert('QTY Order belum diisi!');
            $('#txtQty').focus();
            oke = 0;
        }
        if (oke == 1) {
            if (stokin_no == 0) {
                $.ajax({
                    type: "POST",
                    url: urm,
                    data: $("#frm").serialize(), // serializes the form's elements.
                    success: function (data) {
                        //alert(data); // show response from the php script.
                        $("#txtStokInNo").val(data);
                        stokin_no = data;
                        var datx = {stokin_no: stokin_no, sku: sku, qty: qty};
                        $.ajax({
                            type: "POST",
                            url: urd,
                            data: datx,
                            success: function (dta) {
                                //alert(dta); // show response from the php script.
                                var table = $('#tableDetail').DataTable();
                                var stotal = 0;
                                table.ajax.reload(null, false);
                                var urs = "<?php print($helper->site_url("trx/salepusat/getSubTotal/"));?>" + stokin_no;
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
                var data = {stokin_no: stokin_no, sku: sku, qty: qty};
                $.ajax({
                    type: "POST",
                    url: urd,
                    data: data,
                    success: function (dtz) {
                        //alert(dtz); // show response from the php script.
                        var table = $('#tableDetail').DataTable();
                        var stotal = 0;
                        table.ajax.reload(null, false);
                        var urs = "<?php print($helper->site_url("trx/salepusat/getSubTotal/"));?>" + stokin_no;
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
</script>
</body>
</html>
