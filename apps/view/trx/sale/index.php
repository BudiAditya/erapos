<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $sales Sale[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Daftar Penjualan </title>

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
        @media print {

            /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body */
            body.modal-open {
                visibility: hidden;
            }

            /* body.modal-open .modal .modal-header, */
            body.modal-open .modal .modal-body {
                size: auto;
                margin: 0mm;
                visibility: visible; /* make visible modal body and header */
            }
        }
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
                                <h2>DAFTAR PENJUALAN</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p>
                                    <button type="button" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> PENJUALAN (POS)</button>
                                </p>
                                <table id="tableSales" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <!--<th>Outlet</th>-->
                                        <th>No.Trx</th>
                                        <th>Tanggal</th>
                                        <th>Table</th>
                                        <th>Customer</th>
                                        <th>Jumlah</th>
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
        <div id="modalView" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">NOTA PENJUALAN</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <span id="divNamaOutlet" class="col-sm-12" style="text-align: center;font-size: 18px;font-weight: bold"></span></h4>
                            </div>
                            <div class="form-group">
                                <span id="divAlamatOutlet" class="col-sm-12" style="text-align: center"></span>
                            </div>
                            <div class="separator"></div>
                            <div class="form-group">
                                <div class="col-sm-4 text-left" id="idTrxNo"></div>
                                <div class="col-sm-4 text-left" id="idCustomer"></div>
                                <div class="col-sm-4 text-left" id="idTime"></div>
                                <input type="hidden" id="txtTrxNo">
                                <input type="hidden" id="txtTrxStatus">
                            </div>
                            <div class="separator"></div>
                            <table class="table" id="tableDetail" width="100%">
                                <thead>
                                <tr>
                                    <th>QTY</th>
                                    <!--<th>SAT</th>-->
                                    <th>MENU PILIHAN</th>
                                    <th>HARGA</th>
                                    <th>JUMLAH</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="separator"></div>
                            <div class="form-group" id="divNotes" hidden>
                                <span style="text-align: left;" class="col-sm-12 pull-left" id="idNotes">NOTE:</span>
                            </div>
                            <div class="form-group">
                                <span style="text-align: left;" class="col-sm-6 bold pull-left" id="idTrxStatus">-STATUS-</span>
                                <span style="text-align: right;" class="col-sm-6 bold pull-right" id="idSubTotal">SUB TOTAL Rp.</span>
                                <span style="text-align: right;" class="col-sm-12 bold pull-right" id="idDiscount">DISKON ..% Rp.</span>
                                <span style="text-align: right;" class="col-sm-12 bold pull-right" id="idPajak">PAJAK 10% Rp.</span>
                                <span style="text-align: right;" class="col-sm-12 bold pull-right" id="idTotal">TOTAL Rp.</span>
                                <span style="text-align: right;" class="col-sm-12 bold pull-right" id="idCash">CASH Rp.</span>
                                <span style="text-align: right;" class="col-sm-12 bold pull-right" id="idChange">KEMBALI Rp.</span>
                            </div>
                            <div class="form-group">
                                <span style="text-align: center" class="col-sm-12">**Terima kasih atas kunjungan Anda**</span>
                            </div>
                            <!--</form>-->
                        </div>
                        <!--modal footer-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btPrtModal" class="btn btn-default"><i class="fa fa-print"></i> REPRINT</button>
                        <button type="button" id="btClsModal" class="btn btn-default"><i class="fa fa-remove"></i> CLOSE</button>
                    </div>
                    <!--modal-content-->
                </div>
                <!--modal-dialog modal-lg-->
            </div>
            <!--form-kantor-modal-->
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
<!-- direct printing -->
<script src="<?php print($helper->path("public/js/recta.js"));?>"></script>
<!-- actions script -->
<script>
    var printer = new Recta('1122334455', '1811');

    $(document).ready( function ()
    {
        //$('#menu_toggle').click();

        //tampilkan data table
        $('#tableSales').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("trx/sale/getJsonSale"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "trx_no" },
                { "data": "trx_time" },
                { "data": "table_no" },
                { "data": "cust_name" },
                { "data": "fsub_total", "sClass": "rkanan" },
                { "data": "dtrx_status" },
                { "data": "button" }
            ]
        });

        $('#tableDetail').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "responsive": false,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("trx/sale/getJsonSaleDetail"));?>",
                "type": "POST",
                "data": function ( d ) {d.trx_no = document.getElementById("txtTrxNo").value}
            },
            "columns": [
                { "data": "qty", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "sub_total", "sClass": "rkanan" }
            ]
        });
    });

    $('#btClsModal').click(function (e) {
        $("#modalView").modal('hide');
    });

    $('#btPrtModal').click(function (e) {
        var trx_no = $("#txtTrxNo").val();
        //js:window.print();
        printStruk(trx_no);
        //printElem(modalView);
        $("#modalView").modal('hide');
    });

    //proses tambah data
    $('#btAdd').click(function (e) {
        var oid = '<?php print($outletId);?>';
        var urx = "<?php print($helper->site_url("trx/sale/add"));?>";
        if (oid == 1){
            swal("Error!", '-PUSAT- tidak boleh melakukan penjualan langsung!', "error");
        }else {
            location.href = urx;
        }
    });

    //proses view
    $(document).on("click",".btsEdit",function(){
        var trx_no = $(this).attr("trx_no");
        var dvalue = {trx_no: trx_no};
        var urx = "<?php print($helper->site_url("trx/sale/getTrxData"));?>";
        var urz = "<?php print($helper->site_url("trx/sale/edit/"));?>"+trx_no;
        $.ajax(
            {
                url : urx,
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    if (data.trx_status == 0){
                        location.href = urz;
                    }else{
                        swal("Error!", 'Transaksi sudah diproses!', "error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
            });
    });

    //proses hapus data
    $(document).on( "click",".btsDelete", function() {
        var id_sale = $(this).attr("id_sale");
        var trx_no = $(this).attr("trx_no");
        var trx_status = $(this).attr("trx_status");
        var xtitle = null;
        var tbutton = null;
        if (trx_status == 0){
            xtitle = "Hapus Data Penjualan";
            tbutton = "Hapus";
        }else {
            xtitle = "Void Data Penjualan";
            tbutton = "Void";
        }
        swal({
                title: xtitle,
                text: "Transaksi No: "+trx_no+" ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: tbutton,
                closeOnConfirm: true },
            function(){
                var dvalue = {trx_no: trx_no};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("trx/sale/delete"));?>",
                        type: "POST",
                        data : dvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil proses data penjualan!');
                                var table = $('#tableSales').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal proses data penjualan, Error : "+data.error,"error");
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            swal("Error!", textStatus, "error");
                        }
                    });
            });
    });

    //proses view
    $(document).on("click",".btsView",function(){
        var trx_no = $(this).attr("trx_no");
        var dvalue = {trx_no: trx_no};
        $.ajax(
            {
                url : "<?php print($helper->site_url("trx/sale/getTrxData"));?>",
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    $("#divNamaOutlet").text(data.outlet_name);
                    $("#divAlamatOutlet").text(data.outlet_alamat);
                    $("#idTrxNo").text('#'+trx_no);
                    $("#idCustomer").text(data.cust_code+' T: #'+data.table_no);
                    $("#idTime").text(data.trx_time);
                    if (data.notes != '') {
                        $("#divNotes").show();
                        $("#idNotes").text('NOTE: ' + data.notes);
                    }else{
                        $("#divNotes").hide();
                    }
                    $("#idTrxStatus").text('*'+data.dtrx_status+'*');
                    $("#idSubTotal").text('SUB TOTAL: Rp. '+Intl.NumberFormat().format(data.sub_total));
                    if (data.disc_amt + data.tax_amt == 0){
                        $("#idSubTotal").hide();
                    }else {
                        $("#idSubTotal").show();
                    }
                    if (data.disc_amt > 0){
                        $("#idDiscount").text('DISKON '+data.disc_pct+'% : Rp. '+Intl.NumberFormat().format(data.disc_amt));
                        $("#idDiscount").show();
                    }else{
                        $("#idDiscount").hide();
                    }
                    if (data.tax_amt > 0){
                        $("#idPajak").text('PAJAK '+data.tax_pct+'% : Rp. '+Intl.NumberFormat().format(data.tax_amt));
                        $("#idPajak").show();
                    }else{
                        $("#idPajak").hide();
                    }
                    $("#idTotal").text('TOTAL: Rp. '+Intl.NumberFormat().format(data.total_amt));
                    if ((data.cash_amt - data.total_amt) > 0){
                        $("#idCash").text('CASH : Rp. '+Intl.NumberFormat().format(data.cash_amt));
                        $("#idChange").text('KEMBALI : Rp. '+Intl.NumberFormat().format((data.cash_amt - data.total_amt)));
                        $("#idCash").show();
                        $("#idChange").show();
                    }else{
                        $("#idCash").hide();
                        $("#idChange").hide();
                    }
                    $("#txtTrxNo").val(trx_no);
                    $("#txtTrxStatus").val(data.trx_status);
                    if (data.trx_status == 0){
                        $("#btPrtModal").prop('disabled', true);
                    }else{
                        $("#btPrtModal").prop('disabled', false);
                    }
                    var table = $('#tableDetail').DataTable();
                    table.ajax.reload( null, false );
                    $("#modalView").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
            });
    });

    //notify
    $.notifyDefaults({
        type: 'success',
        delay: 500
    });

    // test direct printing
    function testPrint () {
        printer.open().then(function () {
            printer.align('center')
                .text('Hello World !!')
                .text('1234567890123456789012345678901234567890')
                .bold(true)
                .text('This is bold text')
                .bold(false)
                .underline(true)
                .text('This is underline text')
                .underline(false)
                .barcode('UPC-A', '123456789012')
                .feed(5)
                .cut()
                .print()
        })
    }

    // print struk
    function printStruk (trx_no) {
        var urx = "<?php print($helper->site_url("trx/sale/getStrukData"));?>";
        var dvalue = {trx_no: trx_no};
        $.ajax(
            {
                url : urx,
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    printer.open().then(function () {
                        $.each(JSON.parse(data), function () {
                            $.each(this, function (name, value) {
                                //console.log(name + '=' + value);
                                if (name == 'format'){
                                    switch(value) {
                                        case "AC":
                                            printer.align('center');
                                            break;
                                        case "AL":
                                            printer.align('left');
                                            break;
                                        case "AR":
                                            printer.align('right');
                                            break;
                                        case "B1":
                                            printer.bold(true);
                                            break;
                                        case "B0":
                                            printer.bold(false);
                                            break;
                                        case "U1":
                                            printer.underline(true);
                                            break;
                                        case "U0":
                                            printer.underline(false);
                                            break;
                                        default:
                                            printer.align('left');
                                            break;
                                    }
                                }else {
                                    printer.text(value);
                                }
                            });
                        });
                        printer.feed(5);
                        printer.cut();
                        printer.print();
                    })
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
            });
    }
</script>
</body>
</html>
