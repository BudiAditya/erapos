<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $produks Produk[] */
/** @var $customers Customer[] */
/** @var $sale Sale */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EraPOS | Point Of Sale (POS) </title>
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
        @media print {
            /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body */
            body.modal-open {
                visibility: hidden;
            }

            /*body.modal-open .modal .modal-header,*/
            body.modal-open .modal .modal-body {
                visibility: visible; /* make visible modal body and header */
            }
        }
        .rkanan { text-align: right; }
        .rcenter { text-align: center; }

        textarea{
            height: 50px;
            width: 98%;
            padding:1%;
        }
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
                                <h2><b>POINT OF SALE (POS)</b></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="pad" id="infopanel"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table-hover table-striped" id="tableProduk" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>SKU</th>
                                                    <th>Nama Menu</th>
                                                    <th>Harga</th>
                                                    <th>Aksi</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $i = 0;
                                                foreach ($produks as $produk){
                                                    print('<tr>');
                                                    printf('<td>%s</td>',$produk->Sku);
                                                    printf('<td>%s</td>',strtoupper($produk->Nama));
                                                    printf('<td align="right"><b>%s</b></td>',number_format($produk->HrgJual,0));
                                                    printf('<td align="right"><button type="button" id="%d" sku="%s" class="btn btn-success btn-xs btpAdd" ><i class="fa fa-arrow-right"></i></button></td>',$produk->Id,$produk->Sku);
                                                    print('</tr>');
                                                    $i++;
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <form id="frm" class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label for="cboCustCode" class="col-md-2 control-label">CUSTOMER</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select class="form-control" id="cboCustCode" name="CustCode" required>
                                                    <?php
                                                    foreach ($customers as $cust) {
                                                        if ($cust->Kode == $sale->CustCode){
                                                            printf('<option value="%s" selected="selected">%s - %s</option>', $cust->Kode, $cust->Kode, $cust->Nama);
                                                        }else {
                                                            printf('<option value="%s">%s - %s</option>', $cust->Kode, $cust->Kode, $cust->Nama);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" id="txtTrxNo" name="TrxNo" value="<?php print($sale->TrxNo);?>">
                                            </div>
                                            <!--<div class="col-md-2"><button type="button" class="btn btn-primary btn-sm" id="btAddCustomer" name="btAdd"><i class="fa fa-plus"></i></button></div>-->
                                            <label for="txtTableNo" class="col-sm-2 control-label">TABLE</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtTableNo" placeholder="Meja" name="TableNo" value="<?php print($sale->TableNo);?>" style="text-align: center;font-weight: bold">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group" id="divDetail">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-striped table-hover" id="tableDetail" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th width="20%" align="left">AKSI</th>
                                                        <th width="10%" align="center">QTY</th>
                                                        <th width="50">MENU</th>
                                                        <th width="15%" align="right">JUMLAH</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <label for="txtSubTotal" class="col-md-6 col-sm-6 col-xs-12 control-label">SUB TOTAL</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" class="form-control num" id="txtSubTotal" placeholder="Sub Total" name="SubTotal" value="0" readonly style="text-align: right;font-size: 20px;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtDiscPct" class="col-md-6 col-sm-6 col-xs-12 control-label">DISKON %</label>
                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                <input type="text" class="form-control num" id="txtDiscPct" name="DiscPct" value="0" style="text-align: right;font-size: 20px;">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                <input type="text" class="form-control num" id="txtDiscAmt" name="DiscAmt" value="0" readonly style="text-align: right;font-size: 20px;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="isTaxable" class="col-md-6 col-sm-6 col-xs-12 control-label">PAJAK 10%</label>
                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                <input type="checkbox" class="form-control" id="isTaxable" name="IsTaxable" value="1">
                                                <input type="hidden" id="txtTaxPct" name="TaxPct" value="10">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-6">
                                                <input type="text" class="form-control num" id="txtTaxAmt" name="TaxAmt" value="0" readonly style="text-align: right;font-size: 20px;">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <label for="txtTotal" class="col-md-6 col-sm-6 col-xs-12 control-label">T O T A L</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <b>
                                                    <input type="text" class="form-control num" id="txtTotal" placeholder="Sub Total" name="GrandTotal" value="0" readonly style="text-align: right;font-size: 20px;">
                                                </b>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea class="form-input" id="txtNotes" name="Notes" placeholder="** Catatan Pesanan **" style="width: 100%"><?php print($sale->Notes);?></textarea>
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <?php if ($userLvl > 1){ ?>
                                            <button type="button" class="btn btn-primary" id="btBayar"><i class="fa fa-save"></i> BAYAR</button>
                                            <button type="button" id="btPrint" class="btn btn-default"><i class="fa fa-print"></i> CETAK</button>
                                            <button type="button" class="btn btn-warning" id="btVoid"><i class="fa fa-remove"></i> VOID</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-default" id="btClose"><i class="fa fa-remove"></i> <span id="btCloseCaption">TUTUP</span></button>
                                        </div>
                                    </form>
                                </div>
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
        <div id="modalPembayaran" class="modal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle"><b>PROSES PEMBAYARAN</b></div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="tSubTotal" class="col-sm-4  control-label">TOTAL</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control num" id="tSubTotal" placeholder="Sub Total" value="0" style="text-align: right;font-size: 25px" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tMoneyAmt" class="col-sm-4  control-label">JUM UANG</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control num" id="tMoneyAmt" placeholder="Jumlah Uang" value="0" style="text-align: right;font-size: 25px" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tKembalian" class="col-sm-4  control-label">KEMBALIAN</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control num" id="tKembalian" placeholder="KEMBALIAN" value="0" style="text-align: right;font-size: 25px" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 pull-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="btbProses"><i class="fa fa-save"></i> BAYAR</button>
                                    <button type="button" class="btn btn-default btn-sm" id="btbCancel"><i class="fa fa-close"></i> BATAL</button>
                                </div>
                            </div>
                        </div>
                        <!--modal footer-->
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
<!-- jConfirm -->
<script src="<?php print($helper->path("assets/cdn/js/jquery-confirm.min.js"));?>"></script>
<!-- autonumeric -->
<script src="<?php print($helper->path("public/js/auto-numeric.js"));?>"></script>
<!-- direct printing -->
<script src="<?php print($helper->path("public/js/recta.js"));?>"></script>
<script>

    //printer declare
    var printer = new Recta('1122334455', '1811');

    $(document).ready( function ()
    {
       // var elements = ["tMoneyAmt", "btbProses"];
        //BatchFocusRegister(elements);

        //$('#menu_toggle').click();

        //tampilkan data menu
        $('#tableProduk').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "responsive": false,
            "autoWidth": true,
            "pageLength": 10
        });

        //tampilkan data menu yg dipilih
        var trx_no = $("#txtTrxNo").val();
        var dvalue = {trx_no: trx_no};
        $('#tableDetail').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "responsive": false,
            "autoWidth": true,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("trx/sale/getJsonSaleDetail"));?>",
                "type": "POST",
                "data": function ( d ) {d.trx_no = document.getElementById("txtTrxNo").value}
            },
            "columns": [
                { "data": "button" },
                { "data": "qty", "sClass": "rcenter" },
                { "data": "nama" },
                { "data": "sub_total", "sClass": "rkanan" }
            ]
        });

        $('#tableDprint').DataTable({
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
                //{ "data": "satuan" },
                { "data": "nama" },
                { "data": "fharga", "sClass": "rkanan" },
                { "data": "sub_total", "sClass": "rkanan" }
            ]
        });

        $("#btbProses").click(function (e) {
            var ukembali = Number($("#tKembalian").val().replace(",",""));
            var jbayar = Number($("#tMoneyAmt").val().replace(",",""));
            var trx_no = $("#txtTrxNo").val();
            var table_no = $("#txtTableNo").val();
            var notes = $("#txtNotes").val();
            var is_tax = 0;
            var sub_total = Number($("#txtSubTotal").val().replace(",",""));
            var disc_pct = Number($("#txtDiscPct").val().replace(",",""));
            var disc_amt = Number($("#txtDiscAmt").val().replace(",",""));
            var tax_pct = Number($("#txtTaxPct").val().replace(",",""));
            var tax_amt = Number($("#txtTaxAmt").val().replace(",",""));
            var grand_total = Number($("#txtTotal").val().replace(",",""));
            if (tax_amt > 0){
                is_tax = 1;
            }
            var urb = "<?php print($helper->site_url("trx/sale/bayar"));?>";
            if (ukembali > -1){
                var data = {trx_no: trx_no,pay_amt: jbayar,notes: notes,table_no: table_no,sub_total: sub_total,disc_pct: disc_pct,disc_amt: disc_amt,is_tax: is_tax,tax_pct: tax_pct,tax_amt: tax_amt,grand_total: grand_total};
                $.ajax({
                    type: "POST",
                    url: urb,
                    data: data,
                    success: function(dtz)
                    {
                       //alert(dtz); // show response from the php script.
                       if (dtz == 'OK'){
                           if (confirm ('Print Struk Penjualan?')) {
                               printStruk(trx_no);
                               //testPrint();
                           }
                           location.reload();
                       }else{
                         $.alert('Proses Bayar gagal!');
                       }
                    }
                });
            }
        });

        $("#btbCancel").click(function (e) {
            $("#modalPembayaran").modal("hide");
        });

        $("#tMoneyAmt").change(function (e) {
            var stotal = Number($("#tSubTotal").val().replace(",",""));
            var jbayar = Number(this.value.replace(",",""));
            var jkbali = Intl.NumberFormat().format(jbayar - stotal);
            $("#tKembalian").val(jkbali);
        });

        $("#btBayar").click(function (e) {
            //proses bayar disini
            var stotal = $("#txtTotal").val();
            var ntotal = Number($("#txtTotal").val().replace(",",""));
            if (ntotal > 0) {
                $("#tSubTotal").val(stotal);
                $("#tMoneyAmt").val(stotal);
                $("#tKembalian").val(0);
                $("#modalPembayaran").modal("show");
                $("#tMoneyAmt").focus();
                document.getElementById("tMoneyAmt").select();
            }
        });

        $('#tMoneyAmt').on('keypress', function (e) {
            if (e.which === 13) {
                $("#btbProses").focus();
            }
        });

        $("#btPrint").click(function (e) {
            //proses cetak struk disini
            var stotal = $("#txtTotal").val().replace(",","");
            if (stotal > 0) {
                var trx_no = $("#txtTrxNo").val();
                printStruk(trx_no);
            }
        });

        $("#btVoid").click(function (e) {
            //proses batal disini
            var trx_no = $("#txtTrxNo").val();
            var stotal = $("#txtTotal").val().replace(",","");
            if (stotal > 0) {
                swal({
                        title: "Void Penjualan",
                        text: "Trx No: "+trx_no+" ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Void",
                        closeOnConfirm: true },
                        function(){
                            if (trx_no == '0') {
                                location.reload();
                            } else {
                                var urd = "<?php print($helper->site_url("trx/sale/delete"));?>";
                                var data = {trx_no: trx_no};
                                $.ajax({
                                    type: "POST",
                                    url: urd,
                                    data: data,
                                    success: function (dtz) {
                                        //alert(dtz);
                                        location.reload();
                                    }
                                });
                            }
                        })
            }
        });

        $("#btClose").click(function (e) {
            //proses tutup form penjualan disini
            var trx_no = $("#txtTrxNo").val();
            var stotal = $("#txtSubTotal").val();
            var url = "<?php print($helper->site_url("trx/sale"));?>";
            if (stotal == 0) {
                location.href = url;
            }else{
                swal({
                        title: "Pending Transaksi Penjualan",
                        text: "Trx No: "+trx_no+" ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Pending",
                        closeOnConfirm: true },
                    function(){
                        //proses pending
                        var trx_no = $("#txtTrxNo").val();
                        var table_no = $("#txtTableNo").val();
                        var notes = $("#txtNotes").val();
                        var is_tax = 0;
                        var sub_total = Number($("#txtSubTotal").val().replace(",",""));
                        var disc_pct = Number($("#txtDiscPct").val().replace(",",""));
                        var disc_amt = Number($("#txtDiscAmt").val().replace(",",""));
                        var tax_pct = Number($("#txtTaxPct").val().replace(",",""));
                        var tax_amt = Number($("#txtTaxAmt").val().replace(",",""));
                        var grand_total = Number($("#txtTotal").val().replace(",",""));
                        if (tax_amt > 0){
                            is_tax = 1;
                        }
                        var urp = "<?php print($helper->site_url("trx/sale/pending"));?>";
                        var data = {trx_no: trx_no,notes: notes,table_no: table_no,sub_total: sub_total,disc_pct: disc_pct,disc_amt: disc_amt,is_tax: is_tax,tax_pct: tax_pct,tax_amt: tax_amt,grand_total: grand_total};
                        $.ajax({
                            type: "POST",
                            url: urp,
                            data: data,
                            success: function(dtz)
                            {
                                location.href = url;
                            }
                        });
                    })
            }
        });

        $('#btClsModal').click(function (e) {
            $("#modalView").modal('hide');
        });

        $('#btPrtModal').click(function (e) {
            js:window.print();
            $("#modalView").modal('hide');
        });

        //diskon?
        $('#txtDiscPct').change(function (e) {
            checkSubTotal();
        });
        //pajak?
        $('#isTaxable').click(function() {
            checkSubTotal();
        });
    });

    function checkSubTotal() {
        var stot = Number($("#txtSubTotal").val().replace(",",""));
        var dpct = Number($("#txtDiscPct").val());
        var damt = Number($("#txtDiscAmt").val());
        var tpct = Number($("#txtTaxPct").val());
        var itax = $("#isTaxable").val();
        var tamt = Number($("#txtTaxAmt").val());
        var ttal = 0;
        var sdamt = 0;
        var stamt = 0;
        var sttal = 0;
        if (stot == 0){
            $("#btCloseCaption").text('TUTUP');
            $("#txtDiscAmt").val(0);
            $("#txtTaxAmt").val(0);
            $("#txtTotal").val(0);
        }else{
            $("#btCloseCaption").text('PENDING');
            //hitung diskon
            if (dpct > 0){
                damt = stot * (dpct / 100);
            }else{
                damt = 0;
            }
            sdamt = Intl.NumberFormat().format(damt);
            $("#txtDiscAmt").val(sdamt);
            //hitung pajak
            if($('#isTaxable').prop('checked')) {
                tamt = (stot - damt) * (tpct/100);
            }else{
                tamt = 0;
            }
            stamt = Intl.NumberFormat().format(tamt);
            $("#txtTaxAmt").val(stamt);
            //hitung total
            ttal = (stot - damt) + tamt
            sttal = Intl.NumberFormat().format(ttal);
            $("#txtTotal").val(sttal);
        }
        //sekalian hitung2an

    }

    //notify
    $.notifyDefaults({
        type: 'success',
        delay: 500
    });

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
                }
                /*,
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
                */
            });
    }

    function printStrukView(trx_no,preview = true) {
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
                    $("#idTotal").text('TOTAL: Rp. '+Intl.NumberFormat().format(data.sub_total));
                    if ((data.cash_amt - data.sub_total) > 0){
                        $("#idCash").text('CASH : Rp. '+Intl.NumberFormat().format(data.cash_amt));
                        $("#idChange").text('CHANGE : Rp. '+Intl.NumberFormat().format((data.cash_amt - data.sub_total)));
                        $("#idCash").show();
                        $("#idChange").show();
                    }else{
                        $("#idCash").hide();
                        $("#idChange").hide();
                    }
                    if (data.trx_status == 0){
                        $("#btPrtModal").prop('disabled', true);
                    }else{
                        $("#btPrtModal").prop('disabled', false);
                    }
                    var table = $('#tableDprint').DataTable();
                    table.ajax.reload( null, false );
                    $("#modalView").modal("show");
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
            });
    }

/*
    $(window).bind("beforeunload",function(event) {
        if ($("#txtSubTotal").val() > 0) {
           return "Transaksi belum selesai!";
        }
    });
*/
    //numeric format
    $(".num").autoNumeric({mDec: '0'});
    $("#frm").submit(function(e) {
        $(".num").each(function(idx, ele){
            this.value  = $(ele).autoNumericGet({mDec: '0'});
        });
    });

    $(document).on("click",".btdAdd",function(){
        var trx_no = $("#txtTrxNo").val();
        var psku = $(this).attr("sku");
        var urd = "<?php print($helper->site_url("trx/sale/addetail"));?>";
        var data = {trx_no: trx_no,sku: psku,qty: 1};
        $.ajax({
            type: "POST",
            url: urd,
            data: data,
            success: function(dtz)
            {
                //alert(dtz); // show response from the php script.
                var table = $('#tableDetail').DataTable();
                var stotal = 0;
                table.ajax.reload( null, false );
                var urs = "<?php print($helper->site_url("trx/sale/getSubTotal/"));?>"+trx_no;
                $.get(urs, function(data){
                    stotal = Intl.NumberFormat().format(data);
                    $("#txtSubTotal").val(stotal);
                    checkSubTotal();
                });
            }
        });
    });

    $(document).on("click",".btdRemove",function(){
        var trx_no = $("#txtTrxNo").val();
        var psku = $(this).attr("sku");
        var urd = "<?php print($helper->site_url("trx/sale/addetail"));?>";
        var data = {trx_no: trx_no,sku: psku,qty: -1};
        $.ajax({
            type: "POST",
            url: urd,
            data: data,
            success: function(dtz)
            {
                //alert(dtz); // show response from the php script.
                var table = $('#tableDetail').DataTable();
                var stotal = 0;
                table.ajax.reload( null, false );
                var urs = "<?php print($helper->site_url("trx/sale/getSubTotal/"));?>"+trx_no;
                $.get(urs, function(data){
                    stotal = Intl.NumberFormat().format(data);
                    $("#txtSubTotal").val(stotal);
                    checkSubTotal();
                });
            }
        });
    });

    $(document).on("click",".btpAdd",function(){
        var trx_no = $("#txtTrxNo").val();
        var cust_code = $("#cboCustCode").val();
        var id = $(this).attr("id");
        var psku = $(this).attr("sku");
        var urm = "<?php print($helper->site_url("trx/sale/addmaster"));?>";
        var urd = "<?php print($helper->site_url("trx/sale/addetail"));?>";
        if (cust_code == null || cust_code == '0' || cust_code == ''){
            alert('Customer belum diisi!');
            $("#cboCustCode").focus();
        }else {
            if (trx_no == 0) {
                //alert(cust_code);
                $.ajax({
                    type: "POST",
                    url: urm,
                    data: $("#frm").serialize(), // serializes the form's elements.
                    success: function (data) {
                        //alert(data); // show response from the php script.
                        $("#txtTrxNo").val(data);
                        trx_no = data;
                        var datx = {trx_no: trx_no, sku: psku, qty: 1};
                        $.ajax({
                            type: "POST",
                            url: urd,
                            data: datx,
                            success: function (dta) {
                                //alert(dta); // show response from the php script.
                                var table = $('#tableDetail').DataTable();
                                table.ajax.reload(null, false);
                                var stotal = 0;
                                var urs = "<?php print($helper->site_url("trx/sale/getSubTotal/"));?>" + trx_no;
                                $.get(urs, function (data) {
                                    stotal = Intl.NumberFormat().format(data);
                                    $("#txtSubTotal").val(stotal);
                                    checkSubTotal();
                                });
                            }
                        });
                    }
                });
            } else {
                var data = {trx_no: trx_no, sku: psku, qty: 1};
                $.ajax({
                    type: "POST",
                    url: urd,
                    data: data,
                    success: function (dtz) {
                        //alert(dtz); // show response from the php script.
                        var table = $('#tableDetail').DataTable();
                        var stotal = 0;
                        table.ajax.reload(null, false);
                        var urs = "<?php print($helper->site_url("trx/sale/getSubTotal/"));?>" + trx_no;
                        $.get(urs, function (data) {
                            stotal = Intl.NumberFormat().format(data);
                            $("#txtSubTotal").val(stotal);
                            checkSubTotal();
                        });
                    }
                });
            }
        }
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
</script>
</body>
</html>
