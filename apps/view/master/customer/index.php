<!DOCTYPE html>
<?php
/** @var $customers Customer[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Customer </title>

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
                                <h2>DAFTAR CUSTOMER</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> Add Customer</button>
                                </p>
                                <table id="tableCustomer" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Email</th>
                                        <th>Phone</th>
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
        <div id="modalCustomer" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Data Customer</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="txtKode" class="col-sm-3  control-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtKode" placeholder="Kode Customer" readonly>
                                    <input type="hidden" id="crudMethod" value="">
                                    <input type="hidden" id="txtId" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtNama" class="col-sm-3  control-label">Nama Customer</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtNama" placeholder="Nama Customer" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtAlamat" class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtAlamat" placeholder="Alamat Customer" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtKota" class="col-sm-3  control-label">K o t a</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtKota" placeholder="Kota" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtEmail" class="col-sm-3  control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtEmail" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtPhone" class="col-sm-3  control-label">No. Telephone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="txtPhone" placeholder="No. Telephone" required>
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
<script>
    $(document).ready( function ()
    {
        //tampilkan data table
        $('#tableCustomer').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("master/customer/getJsonCustomer"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "kode" },
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "kota" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "button" }
            ]
        });


    });

    //proses tambah data
    $(document).on("click","#btAdd",function(){
        $("#modalCustomer").modal("show");
        $("#frmTitle").text("Tambah Data Customer");
        $("#txtKode").focus();
        $("#txtNama").val("");
        $("#txtAlamat").val("");
        $("#txtKota").val("");
        $("#txtEmail").val("");
        $("#txtPhone").val("");
        $("#crudMethod").val("N");
        $("#txtId").val("0");
    });

    //proses hapus data
    $(document).on( "click",".btDelete", function() {
        var id_customer = $(this).attr("id_customer");
        var ckode = $(this).attr("kode");
        var cname = $(this).attr("nama");
        swal({
                title: "Hapus Data",
                text: "Hapus Data Customer : "+cname+" ("+ckode+") ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true },
            function(){
                var idvalue = {id: id_customer};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("master/customer/delete"));?>",
                        type: "POST",
                        data : idvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data customer!');
                                var table = $('#tableCustomer').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal hapus data customer, Error : "+data.error,"error");
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
        var customer_id = $("#txtId").val();
        var kode = $("#txtKode").val();
        var nama = $("#txtNama").val();
        var kota = $("#txtKota").val();
        var alamat = $("#txtAlamat").val();
        var email = $("#txtEmail").val();
        var phone = $("#txtPhone").val();
        var crud = $("#crudMethod").val();
        var urx = null;
        /*
        if (kode == '' || kode == null ){
            swal("Warning","Kode Customer harus diisi!","warning");
            $("#txtKode").focus();
            return;
        }
        */
        if (nama == '' || nama == null ){
            swal("Warning","Nama Customer harus diisi!","warning");
            $("#txtNama").focus();
            return;
        }
        if (crud == 'N'){
            urx = "<?php print($helper->site_url("master/customer/add"));?>";
        }else if (crud == 'E'){
            urx = "<?php print($helper->site_url("master/customer/edit"));?>"
        }
        var dvalue = {
            Id: customer_id,
            Kode: kode,
            Nama: nama,
            Alamat: alamat,
            Kota: kota,
            Email: email,
            Phone: phone,
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
                            var table = $('#tableCustomer').DataTable();
                            table.ajax.reload( null, false );
                            //$("#txtKode").focus();
                            $("#txtKode").val("");
                            $("#txtNama").val("");
                            $("#txtAlamat").val("");
                            $("#txtKota").val("");
                            $("#txtEmail").val("");
                            $("#txtPhone").val("");
                            $("#crudMethod").val("N");
                            $("#txtId").val("0");
                            $("#modalCustomer").modal('hide');
                        }else{
                            swal("Error","Gagal simpan data customer, Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            var table = $('#tableCustomer').DataTable();
                            table.ajax.reload( null, false );
                            //$("#txtKode").focus();
                            $("#txtKode").val("");
                            $("#txtNama").val("");
                            $("#txtAlamat").val("");
                            $("#txtKota").val("");
                            $("#txtEmail").val("");
                            $("#txtPhone").val("");
                            $("#crudMethod").val("E");
                            $("#txtId").val("0");
                            $("#modalCustomer").modal('hide');
                        }else{
                            swal("Error","Gagal update data customer, Error : "+data.error,"error");
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
        $("#txtKode").val("");
        $("#txtNama").val("");
        $("#txtAlamat").val("");
        $("#txtKota").val("");
        $("#txtEmail").val("");
        $("#txtPhone").val("");
        $("#crudMethod").val("");
        $("#txtId").val("0");
        $("#modalCustomer").modal('hide');
    });
    
    //proses editing
    $(document).on("click",".btEdit",function(){
        var id_customer = $(this).attr("id_customer");
        var dvalue = {id: id_customer};
        $.ajax(
            {
                url : "<?php print($helper->site_url("master/customer/getdata"));?>",
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    $("#frmTitle").text("Edit Data Customer");
                    $("#crudMethod").val("E");
                    $("#txtId").val(data.id);
                    $("#txtKode").val(data.kode);
                    $("#txtNama").val(data.nama);
                    $("#txtAlamat").val(data.alamat);
                    $("#txtKota").val(data.kota);
                    $("#txtEmail").val(data.email);
                    $("#txtPhone").val(data.phone);
                    $("#modalCustomer").modal('show');
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
