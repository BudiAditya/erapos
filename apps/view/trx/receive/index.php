<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Penerimaan Piutang </title>

    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php print($helper->path("assets/vendors/nprogress/nprogress.css"));?>" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css"));?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php print($helper->path("assets/vendors/iCheck/skins/flat/green.css"));?>" rel="stylesheet">
    <!-- SweetAlert  style -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/sweetalert/sweetalert.css"));?>">
    <!-- responsive datatables -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css"));?>">
    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
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
                                <h2>DAFTAR PENERIMAAN PIUTANG</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> Tambah Transaksi</button>
                                </p>
                                <table id="tableReceive" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>Outlet</th>
                                        <th>Trx No</th>
                                        <th>Tanggal</th>
                                        <th>Customer/Outlet</th>
                                        <th>Reff No.</th>
                                        <th>Jumlah</th>
                                        <th>Mode</th>
                                        <th>Status</th>
                                        <?php if ($userLevel > 2){ ?>
                                        <th align="center">Action</th>
                                        <?php } ?>
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
        <div id="modalReceive" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Transaksi Penerimaan Piutang</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="txtTrxDate" class="col-sm-3  control-label">Tanggal</label>
                                <div class="input-group date col-sm-9" id="myDatepicker">
                                    <input type="text" class="form-control" id="txtTrxDate" placeholder="Tanggal" required/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboCustCode" class="col-sm-3  control-label">Customer / Outlet</label>
                                <div class="col-sm-9">
                                    <div id="cbCustomer">
                                        <select class="form-control" name="cboCustCode" id="cboCustCode" required>
                                            <option value="0"> Pilih Customer </option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="crudMethod" value="N">
                                    <input type="hidden" id="txtId" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboReffNo" class="col-sm-3  control-label">No. Invoice</label>
                                <div class="col-sm-9">
                                    <div id="cbOutstanding">
                                        <select class="form-control" name="cboReffNo" id="cboReffNo" required>
                                            <option value="0"> Pilih Invoice </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtJumlah" class="col-sm-3  control-label">Jumlah Terima</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtJumlah" placeholder="Jumlah Terima" value="0" style="text-align: right" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3  control-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btSave"><i class="fa fa-save"></i> Save</button>
                                    <button type="submit" class="btn btn-default btn-sm" id="btCancel"><i class="fa fa-close"></i> Cancel</button>
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
<!-- bootstrap-datetimepicker -->
<script src="<?php print($helper->path("assets/vendors/moment/min/moment.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"));?>"></script>
<script>

    $('#myDatepicker').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $(document).ready( function ()
    {
        //tampilkan data table
        $('#tableReceive').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("trx/receive/getJsonReceive"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "outlet_kode" },
                { "data": "trx_no" },
                { "data": "trx_date" },
                { "data": "cust_code" },
                { "data": "reff_no" },
                { "data": "fjumlah", "sClass": "rkanan" },
                { "data": "xmode" },
                { "data": "dtrx_status" }
                <?php if ($userLevel > 2){ ?>
                ,{ "data": "button" }
                <?php } ?>
            ]
        });

        //init supplier lists
        initCbCustomer();
    });

    //suppcode change
    $(document).on('change', "#cboCustCode", function(){
        var csc = $(this).val();
        initCbOutstanding(csc);
    });

    //reffno change
    $(document).on('change', "#cboReffNo", function(){
        var dta = $(this).val();
        dta = dta.split('|');
        $("#txtJumlah").val(dta[1]);
    });

    //proses tambah data
    $(document).on("click","#btAdd",function(){
        ClearForm();
        $("#modalReceive").modal("show");
        $("#frmTitle").text("Transaksi Pembayaran Tagihan");
        $("#txtTrxDate").focus();
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    });

    //proses hapus data
    $(document).on( "click",".btDelete", function() {
        var pid = $(this).attr("id_receive");
        var ptrxno = $(this).attr("trx_no");
        var ptrxmode = $(this).attr("trx_mode");
        if (ptrxmode == 1) {
            swal({
                    title: "Hapus Data Pembayaran Tagihan",
                    text: "No : " + ptrxno + " ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete",
                    closeOnConfirm: true
                },
                function () {
                    var idvalue = {id: pid};
                    $.ajax(
                        {
                            url: "<?php print($helper->site_url("trx/receive/delete"));?>",
                            type: "POST",
                            data: idvalue,
                            success: function (data, textStatus, jqXHR) {
                                var data = jQuery.parseJSON(data);
                                if (data.result == 1) {
                                    $.notify('Berhasil hapus pembayaran tagihan!');
                                    var table = $('#tableReceive').DataTable();
                                    table.ajax.reload(null, false);
                                } else {
                                    swal("Error", "Gagal hapus pembayaran tagihan, Error : " + data.error, "error");
                                }

                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal("Error!", textStatus, "error");
                            }
                        });
                });
        }else{
            swal("Error!", "Transaksi -Auto- tidak boleh dihapus langsung!", "error");
        }
    });

    //proses simpan data
    $(document).on("click","#btSave",function(){
        var tid = $("#txtId").val();
        var ttrxdate = $("#txtTrxDate").val();
        var tsuppcode = $("#cboCustCode").val();
        var dreffno = $("#cboReffNo").val();
        dreffno = dreffno.split('|');
        treffno = dreffno[0];
        var tjumlah = $("#txtJumlah").val();
        var crud = $("#crudMethod").val();
        var urx = null;
        if (ttrxdate == '' || ttrxdate == null ){
            swal("Warning","Tanggal harus diisi!","warning");
            $("#txtTrxDate").focus();
            return;
        }
        if (tsuppcode == '0' || tsuppcode == null ){
            swal("Warning","Nama Customer harus dipilih!","warning");
            $("#cboCustCode").focus();
            return;
        }
        if (treffno == '' || treffno == null ){
            swal("Warning","No. Tagihan harus dipilih!","warning");
            $("#cboReffNo").focus();
            return;
        }
        if (tjumlah == '0' || tjumlah == null ){
            swal("Warning","Jumlah Transaksi harus diisi!","warning");
            $("#txtJumlah").focus();
            return;
        }
        if (crud == 'N'){
            urx = "<?php print($helper->site_url("trx/receive/add"));?>";
        }else if (crud == 'E'){
            urx = "<?php print($helper->site_url("trx/receive/edit"));?>"
        }
        var dvalue = {
            Id: tid,
            TrxDate: ttrxdate,
            CustCode: tsuppcode,
            ReffNo: treffno,
            Jumlah: tjumlah,
            Crud:crud
        };

        $.ajax(
            {
                url : urx,
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    if(data.crud == 'N'){
                        if(data.result == 1){
                            $.notify('Successfull save data');
                            var table = $('#tableReceive').DataTable();
                            table.ajax.reload( null, false );
                            ClearForm();
                            $("#modalReceive").modal('hide');
                        }else{
                            swal("Error","Gagal simpan pembayaran tagihan, Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            var table = $('#tableReceive').DataTable();
                            table.ajax.reload( null, false );
                            ClearForm();
                            $("#modalReceive").modal('hide');
                        }else{
                            swal("Error","Gagal update pembayaran tagihan, Error : "+data.error,"error");
                        }
                    }else{

                        swal("Error","Invalid Process!","error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    swal("Error!", textStatus, "error");
                }
            });
    });

    //batalkan proses
    $(document).on("click","#btCancel",function(){
        ClearForm();
        $("#modalReceive").modal('hide');
    });

    //notify
    $.notifyDefaults({
        type: 'success',
        delay: 500
    });

    function ClearForm() {
        $("#txtTrxDate").val("");
        $("#cboCustCode").val("0");
        $("#cboReffNo").val("0");
        $("#txtJumlah").val("0");
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    }

    function initCbCustomer() {
        var urx = "<?php print($helper->site_url("trx/receive/getCustomerByOutletId"));?>";
        $("#cbCustomer").html('');
        $.get(urx, function(dtx) {
            $("#cbCustomer").html(dtx);
        });
    }

    function initCbOutstanding(custcode) {
        var urx = "<?php print($helper->site_url("trx/receive/getOutstandingByCustCode/"));?>"+custcode;
        if (custcode.length > 0) {
            $("#cbOutstanding").html('');
            $.get(urx, function (dtx) {
                $("#cbOutstanding").html(dtx);
            });
        }
    }

</script>
</body>
</html>
