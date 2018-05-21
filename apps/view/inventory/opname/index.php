<!DOCTYPE html>
<?php
/** @var $bahan Produk[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Stok Opname Bahan/Produk</title>

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
                                <h2>STOK OPNAME BAHAN</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> Add Opname</button>
                                </p>
                                <table id="tableOpname" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Opname</th>
                                        <th>SKU</th>
                                        <th>Nama Bahan</th>
                                        <th>Satuan</th>
                                        <th>QTY</th>
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
        <div id="modalOpname" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Data Opname</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="txtTanggal" class="col-sm-3  control-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtTanggal" placeholder="Tanggal" name="txtTanggal">
                                    <input type="hidden" id="crudMethod" value="">
                                    <input type="hidden" id="txtId" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboOpType" class="col-sm-3  control-label">Jenis Opname</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="cboOpType" required>
                                        <option value=""></option>
                                        <option value="1">1 - Stok Awal</option>
                                        <option value="2">2 - Koreksi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboSku" class="col-sm-3  control-label">Nama Bahan</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="cboSku" >
                                        <option value=""></option>
                                        <?php
                                        foreach ($bahan as $produk){
                                            printf('<option value="%s">%s - %s</option>', $produk->Sku, $produk->Sku, $produk->Nama);
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtQty" class="col-sm-3  control-label">Qty</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtQty" value="0" required>
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
<!-- datepicker -->
<script src="<?php print($helper->path("assets/plugins/datepicker/bootstrap-datepicker.js"));?>"></script>
<script>
    $(document).ready( function ()
    {

        //Date picker
        $('#txtTanggal').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true
        });

        //tampilkan data table
        $('#tableOpname').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("inventory/opname/getJsonOpname"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "tanggal" },
                { "data": "jns_opname" },
                { "data": "sku" },
                { "data": "nm_produk" },
                { "data": "satuan" },
                { "data": "qty" },
                { "data": "button" }
            ]
        });


    });

    //proses tambah data
    $(document).on("click","#btAdd",function(){
        $("#modalOpname").modal("show");
        $("#frmTitle").text("Tambah Data Opname Bahan");
        $("#txtTanggal").focus();
        $("#cboOpType").val("");
        $("#cboSku").val("");
        $("#txtQty").val("0");
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    });

    //proses hapus data
    $(document).on( "click",".btDelete", function() {
        var id_opname = $(this).attr("id_opname");
        var ctanggal = $(this).attr("tanggal");
        var cname = $(this).attr("sku");
        swal({
                title: "Hapus Data",
                text: "Hapus Data Opname : "+cname+" ("+ctanggal+") ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true },
            function(){
                var idvalue = {id: id_opname};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("inventory/opname/delete"));?>",
                        type: "POST",
                        data : idvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data opname!');
                                var table = $('#tableOpname').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal hapus data opname, Error : "+data.error,"error");
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            swal("Error!", textStatus, "error");
                        }
                    });
            });
    });

    //proses simpan data
    $(document).on("click","#btSave",function(){
        var opname_id = $("#txtId").val();
        var tanggal = $("#txtTanggal").val();
        var sku = $("#cboSku").val();
        var optype = $("#cboOpType").val();
        var qty = $("#txtQty").val();
        var crud = $("#crudMethod").val();
        var urx = null;

        if (tanggal == '' || tanggal == null ){
            swal("Warning","Tanggal Opname harus diisi!","warning");
            $("#txtTanggal").focus();
            return;
        }

        if (optype == '' || optype == null ){
            swal("Warning","Jenis Opname harus diisi!","warning");
            $("#cboOpType").focus();
            return;
        }

        if (sku == '' || sku == null ){
            swal("Warning","Nama Bahan/SKU harus diisi!","warning");
            $("#cboSku").focus();
            return;
        }

        if (qty == '' || qty == null || qty == 0){
            swal("Warning","QTY Opname harus diisi!","warning");
            $("#txtQty").focus();
            return;
        }

        if (crud == 'N'){
            urx = "<?php print($helper->site_url("inventory/opname/add"));?>";
        }else if (crud == 'E'){
            urx = "<?php print($helper->site_url("inventory/opname/edit"));?>"
        }
        var dvalue = {
            Id: opname_id,
            Tanggal: tanggal,
            OpType: optype,
            Sku: sku,
            Qty: qty,
            Crud: crud
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
                            var table = $('#tableOpname').DataTable();
                            table.ajax.reload( null, false );
                            $("#cboOpType").val("");
                            $("#cboSku").val("");
                            $("#txtQty").val("0");
                            $("#crudMethod").val("N");
                            $("#txtId").val("0");
                            $("#modalOpname").modal('hide');
                        }else{
                            swal("Error","Gagal simpan data opname, Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            var table = $('#tableOpname').DataTable();
                            table.ajax.reload( null, false );
                            $("#cboOpType").val("");
                            $("#cboSku").val("");
                            $("#txtQty").val("0");
                            $("#crudMethod").val("E");
                            $("#txtId").val("0");
                            $("#modalOpname").modal('hide');
                        }else{
                            swal("Error","Gagal update data opname, Error : "+data.error,"error");
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
        $("#txtTanggal").val("");
        $("#cboOpType").val("");
        $("#cboSku").val("");
        $("#txtQty").val("0");
        $("#crudMethod").val("");
        $("#txtId").val("0");
        $("#modalOpname").modal('hide');
    });
    
    //proses editing
    $(document).on("click",".btEdit",function(){
        var id_opname = $(this).attr("id_opname");
        var dvalue = {id: id_opname};
        $.ajax(
            {
                url : "<?php print($helper->site_url("inventory/opname/getdata"));?>",
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    $("#frmTitle").text("Edit Data Opname");
                    $("#crudMethod").val("E");
                    $("#txtId").val(data.id);
                    $("#txtTanggal").val(data.tanggal);
                    $("#cboOpType").val(data.op_type);
                    $("#cboSku").val(data.sku);
                    $("#txtQty").val(data.qty);
                    $("#modalOpname").modal('show');
                    $("#txtNama").focus();
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

    function theFunction(dta) {
        alert(dta);
    }
</script>
</body>
</html>
