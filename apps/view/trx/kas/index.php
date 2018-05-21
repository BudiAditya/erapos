<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Transaksi Kas </title>

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
                                <h2>DAFTAR TRANSAKSI KAS</h2>
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
                                <table id="tableKas" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>Outlet</th>
                                        <th>Trx No</th>
                                        <th>Tanggal</th>
                                        <th>Jns Transaksi</th>
                                        <th>Keterangan</th>
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
        <div id="modalKas" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Data Transaksi Kas</div></h4>
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
                                <label for="cboTrxType" class="col-sm-3  control-label">Jenis Transaksi</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="cboTrxType" >
                                        <option value="0"> Pilih Jenis Transaksi </option>
                                        <option value="1"> Kas Awal </option>
                                        <option value="2"> Pendapatan </option>
                                        <option value="3"> Biaya </option>
                                    </select>
                                    <input type="hidden" id="crudMethod" value="N">
                                    <input type="hidden" id="txtId" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtNotes" class="col-sm-3  control-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtNotes" placeholder="Keterangan Transaksi" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtJumlah" class="col-sm-3  control-label">Jumlah Uang</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtJumlah" placeholder="Jumlah Uang" value="0" style="text-align: right" required>
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
        $('#tableKas').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("trx/kas/getJsonKas"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "outlet_kode" },
                { "data": "trx_no" },
                { "data": "trx_date" },
                { "data": "trx_descs" },
                { "data": "notes" },
                { "data": "fjumlah", "sClass": "rkanan" },
                { "data": "xmode" },
                { "data": "kas_status" }
                <?php if ($userLevel > 2){ ?>
                ,{ "data": "button" }
                <?php } ?>
            ]
        });
    });

    $(document).on("change","#cboTrxType",function(){
        var trxtype = this.value;
        switch(trxtype) {
            case '1':
                $("#txtNotes").val('Kas Awal');
                break;
            case '2':
                $("#txtNotes").val('Pendapatan');
                break;
            case '3':
                $("#txtNotes").val('Biaya');
                break;
            default:
                $("#txtNotes").val('');
        }

    });

    //proses tambah data
    $(document).on("click","#btAdd",function(){
        ClearForm();
        $("#modalKas").modal("show");
        $("#frmTitle").text("Tambah Data Transaksi Kas");
        $("#txtTrxDate").focus();
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    });

    //proses hapus data
    $(document).on( "click",".btDelete", function() {
        var kid = $(this).attr("id_kas");
        var ktrxno = $(this).attr("trx_no");
        var ktrxmode = $(this).attr("trx_mode");
        if (ktrxmode == 1) {
            swal({
                    title: "Hapus Data Transaksi Kas",
                    text: "No : " + ktrxno + " ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete",
                    closeOnConfirm: true
                },
                function () {
                    var idvalue = {id: kid};
                    $.ajax(
                        {
                            url: "<?php print($helper->site_url("trx/kas/delete"));?>",
                            type: "POST",
                            data: idvalue,
                            success: function (data, textStatus, jqXHR) {
                                var data = jQuery.parseJSON(data);
                                if (data.result == 1) {
                                    $.notify('Berhasil hapus transaksi kas!');
                                    var table = $('#tableKas').DataTable();
                                    table.ajax.reload(null, false);
                                } else {
                                    swal("Error", "Gagal hapus transaksi kas, Error : " + data.error, "error");
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
        var ttrxtype = $("#cboTrxType").val();
        var tnotes = $("#txtNotes").val();
        var tjumlah = $("#txtJumlah").val();
        var crud = $("#crudMethod").val();
        var urx = null;
        if (ttrxdate == '' || ttrxdate == null ){
            swal("Warning","Tanggal harus diisi!","warning");
            $("#txtTrxDate").focus();
            return;
        }
        if (ttrxtype == '0' || ttrxtype == null ){
            swal("Warning","Jenis Transaksi harus dipilih!","warning");
            $("#cboTrxType").focus();
            return;
        }
        if (tnotes == '' || tnotes == null ){
            swal("Warning","Keterangan Transaksi harus diisi!","warning");
            $("#cboTrxType").focus();
            return;
        }
        if (tjumlah == '0' || tjumlah == null ){
            swal("Warning","Jumlah Transaksi harus diisi!","warning");
            $("#txtJumlah").focus();
            return;
        }
        if (crud == 'N'){
            urx = "<?php print($helper->site_url("trx/kas/add"));?>";
        }else if (crud == 'E'){
            urx = "<?php print($helper->site_url("trx/kas/edit"));?>"
        }
        var dvalue = {
            Id: tid,
            TrxDate: ttrxdate,
            TrxType: ttrxtype,
            Notes: tnotes,
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
                            var table = $('#tableKas').DataTable();
                            table.ajax.reload( null, false );
                            ClearForm();
                            $("#modalKas").modal('hide');
                        }else{
                            swal("Error","Gagal simpan transaksi kas, Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            var table = $('#tableKas').DataTable();
                            table.ajax.reload( null, false );
                            ClearForm();
                            $("#modalKas").modal('hide');
                        }else{
                            swal("Error","Gagal update transaksi kas, Error : "+data.error,"error");
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
        $("#modalKas").modal('hide');
    });
    
    //proses editing
    $(document).on("click",".btEdit",function(){
        var id_kategori = $(this).attr("id_kategori");
        var dvalue = {id: id_kategori};
        $.ajax(
            {
                url : "<?php print($helper->site_url("trx/kas/getdata"));?>",
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    $("#frmTitle").text("Edit Data Kas");
                    $("#crudMethod").val("E");
                    $("#txtId").val(data.id);
                    $("#txtKas").val(data.kategori);
                    $("#modalKas").modal('show');
                    $("#txtKas").focus();
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

    function ClearForm() {
        $("#txtTrxDate").val("");
        $("#txtTrxType").val("0");
        $("#txtNotes").val("");
        $("#txtJumlah").val("0");
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    }

    function theFunction(dta) {
        alert(dta);
    }
</script>
</body>
</html>
