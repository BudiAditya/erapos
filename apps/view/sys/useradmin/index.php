<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | User System </title>

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
                                <h2>DAFTAR USER SYSTEM</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <?php if ($userLevel > 2){ ?>
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> Add User</button>
                                </p>
                                <?php } ?>
                                <table id="tableUser" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr class="tableheader">
                                        <th>No.</th>
                                        <th>User Email</th>
                                        <th>User Name</th>
                                        <th>Level</th>
                                        <th>Outlet</th>
                                        <th>Status</th>
                                        <th>Picture</th>
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
        <div id="modalUser" class="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Data User</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="iFphoto" class="col-sm-2 control-label">Foto User</label>
                                <div class="col-sm-6">
                                    <p>
                                        <?php print('<img id="ifphoto" src="" width="200" height="150"/>'); ?>
                                    </p>
                                    <input type="file" id="iFphoto" name="iFphoto" accept="image/*">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboUserLevel" class="col-sm-2  control-label">User Level</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="cboUserLevel" required>
                                        <?php
                                        while ($lvl = $ulevels->FetchAssoc()) {
                                            printf('<option value="%d">%s - %s</option>',$lvl["code"],$lvl["code"],$lvl["short_desc"]);
                                        }
                                        ?>
                                    </select>
                                </div>
                                <label for="txtUserEmail" class="col-sm-2  control-label">User Email</label>
                                <div class="col-sm-4">
                                    <input type="email" class="form-control" id="txtUserEmail" placeholder="Email Address" required>
                                    <input type="hidden" id="crudMethod" value="">
                                    <input type="hidden" id="txtUserUid" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtUserName" class="col-sm-2  control-label">User Name</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="txtUserName" placeholder="Nama User" required>
                                </div>
                                <label for="txtUserPwd" class="col-sm-2  control-label">Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="txtUserPwd" placeholder="Def Password" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cboOutletId" class="col-sm-2  control-label">Def Outlet</label>
                                <div class="col-sm-4">
                                    <select id="cboOutletId" class="form-control" required>
                                        <?php
                                        foreach ($outlets as $otl) {
                                            if ($outletId == $otl->Id){
                                                printf('<option value="%d" selected="selected">%s - %s</option>', $otl->Id, $otl->Kode, $otl->OutletName);
                                            }else{
                                                printf('<option value="%d">%s - %s</option>', $otl->Id, $otl->Kode, $otl->OutletName);
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="checkbox" id="checkIsAktif" name="IsAktif" value="1"> User Aktif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2  control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btSave"><i class="fa fa-save"></i> Save</button>
                                    <button type="submit" class="btn btn-default btn-sm" id="btCancel"><i class="fa fa-close"></i> Cancel</button>
                                </div>
                            </div>
                          <!--</form>-->
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
<script>
    $(document).ready( function ()
    {
        //tampilkan data table
        $('#tableUser').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "ajax": {
                "url": "<?php print($helper->site_url("sys/useradmin/getJsonUsers"));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                { "data": "user_email" },
                { "data": "user_name" },
                { "data": "ulevel" },
                { "data": "kd_outlet" },
                { "data": "user_status" },
                { "data": "user_pics" },
                { "data": "button" }
            ]
        });
    });

    //proses tambah data
    $(document).on("click","#btAdd",function(){
        $("#modalUser").modal("show");
        $("#frmTitle").text("Tambah Data User");
        $("#cboUserLevel").focus();
        $("#iFphoto").val("");
        $("#txtUserEmail").val("");
        $("#txtUserName").val("");
        $("#txtUserPwd").val("");
        $("#cboUserLevel").val("0");
        $("#checkIsAktif").prop('checked', false);
        $("#cboOutletId").val("0");
        $("#crudMethod").val("N");
        $("#txtUserUid").val("0");
    });

    //proses hapus data
    $(document).on( "click",".btuDelete", function() {
        var user_uid = $(this).attr("user_uid");
        var uname = $(this).attr("user_name");
        swal({
                title: "Hapus Data",
                text: "Hapus Data User : "+uname+" ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true },
            function(){
                var idvalue = {id: user_uid};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("sys/useradmin/delete"));?>",
                        type: "POST",
                        data : idvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data user!');
                                var table = $('#tableUser').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal hapus data user, Error : "+data.error,"error");
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
        var user_uid = $("#txtUserUid").val();
        var user_email = $("#txtUserEmail").val();
        var user_name = $("#txtUserName").val();
        var user_pwd = $("#txtUserPwd").val();
        var user_level = $("#cboUserLevel").val();
        var outlet_id = $("#cboOutletId").val();
        if ($('#checkIsAktif').prop('checked')){
            var is_aktif = 1;
        }else {
            var is_aktif = 0;
        }
        var fphoto = $("#iFphoto").val();
        var crud = $("#crudMethod").val();
        var urx = null;
        //validasi
        if (user_level == 0 || user_level == null ){
            swal("Warning","Level User harus dipilih!","warning");
            $("#cboUserLevel").focus();
            return;
        }
        if (user_email == '' || user_email == null ){
            swal("Warning","Email User harus diisi!","warning");
            $("#txtUserEmail").focus();
            return;
        }
        if (user_name == '' || user_name == null ){
            swal("Warning","Nama User harus diisi!","warning");
            $("#txtUserName").focus();
            return;
        }
        if (crud == 'N' && (user_pwd == '' || user_pwd == null)){
            swal("Warning","Password Default harus diisi!","warning");
            $("#txtUserPwd").focus();
            return;
        }
        if (outlet_id == 0){
            swal("Warning","Akses Outlet belum dipilih!","warning");
            $("#cboOutletId").focus();
            return;
        }
        
        if (crud == 'N'){
            urx = "<?php print($helper->site_url("sys/useradmin/add"));?>";
        }else if (crud == 'E'){
            urx = "<?php print($helper->site_url("sys/useradmin/edit"));?>"
        }

        var file_data = $("#iFphoto").prop("files")[0];   // Getting the properties of file from file field
        var form_data = new FormData();                  // Creating object of FormData class
        form_data.append("Fphoto", file_data);           // Appending parameter named file with properties of file_field to form_data
        form_data.append("UserUid", user_uid);               // Adding extra parameters to form_data
        form_data.append("OutletId", outlet_id);
        form_data.append("UserEmail", user_email);
        form_data.append("UserName", user_name);
        form_data.append("UserPwd", user_pwd);
        form_data.append("UserLvl", user_level);
        form_data.append("IsAktif", is_aktif);
        form_data.append("Crud", crud);
        $.ajax(
            {
                url : urx,
                dataType: 'script',
                type: "POST",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR)
                {
                    var data = jQuery.parseJSON(data);
                    if(data.crud == 'N'){
                        if(data.result == 1){
                            $.notify('Successfull save data');
                            var table = $('#tableUser').DataTable();
                            table.ajax.reload( null, false );
                            $("#iFphoto").val("");
                            $("#txtUserEmail").val("");
                            $("#txtUserName").val("");
                            $("#cboUserLevel").val("0");
                            $("#checkIsAktif").prop('checked', false);
                            $("#cboOutletId").val("0");
                            $("#crudMethod").val("N");
                            $("#txtUserUid").val("0");
                            $("#modalUser").modal('hide');
                        }else{
                            swal("Error","Gagal simpan data user, Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            var table = $('#tableUser').DataTable();
                            table.ajax.reload( null, false );
                            $("#iFphoto").val("");
                            $("#txtUserEmail").val("");
                            $("#txtUserName").val("");
                            $("#cboUserLevel").val("0");
                            $("#checkIsAktif").prop('checked', false);
                            $("#cboOutletId").val("0");
                            $("#crudMethod").val("E");
                            $("#txtUserUid").val("0");
                            $("#modalUser").modal('hide');
                        }else{
                            swal("Error","Gagal update data user, Error : "+data.error,"error");
                        }
                    }else{
                        swal("Error","Invalid Process!","error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    //swal("Error!", textStatus, "error");
                    var table = $('#tableUser').DataTable();
                    table.ajax.reload( null, false );
                    $("#modalUser").modal('hide');
                }
            });
    });

    //batalkan proses
    $(document).on("click","#btCancel",function(){
        $("#iFphoto").val("");
        $("#txtUserEmail").val("");
        $("#txtUserName").val("");
        $("#cboUserLevel").val("0");
        $("#checkIsAktif").prop('checked', false);
        $("#crudMethod").val("");
        $("#txtUserUid").val("0");
        $("#modalUser").modal('hide');
    });

    //proses editing
    $(document).on("click",".btuEdit",function(){
        var user_uid = $(this).attr("user_uid");
        var dvalue = {id: user_uid};
        $.ajax(
            {
                url : "<?php print($helper->site_url("sys/useradmin/getdata"));?>",
                type: "POST",
                data : dvalue,
                success: function(data, textStatus, jqXHR)
                {
                    var ulevel = Number('<?php print($userLevel);?>');
                    var data = jQuery.parseJSON(data);
                    $("#frmTitle").text("Edit Data User");
                    $("#crudMethod").val("E");
                    $("#txtUserUid").val(data.user_uid);
                    $("#txtUserEmail").val(data.user_email);
                    $("#txtUserName").val(data.user_name);
                    $("#cboUserLevel").val(data.user_level);
                    $("#cboOutletId").val(data.outlet_id);
                    $("#txtUserPwd").val(null);
                    if (data.is_aktif == 1){
                        $("#checkIsAktif").prop('checked', true);
                    }else {
                        $("#checkIsAktif").prop('checked', false);
                    }
                    //fill images
                    var urz = '<?php print($helper->site_url(""));?>'+data.fphoto;
                    $('#ifphoto').attr('src', urz);
                    $("#iFphoto").val('');

                    if (ulevel < 3){
                        $('#txtUserEmail').prop('readonly', true);
                        $('#txtUserName').prop('readonly', true);
                        $('#checkIsAktif').prop('disabled', true);
                        $('#cboUserLevel').prop('readonly', false);
                    }
                    $("#modalUser").modal('show');
                    $("#txtUserName").focus();
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

    //image view first
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#ifphoto').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#iFphoto").change(function(){
        readURL(this);
    });
</script>
</body>
</html>
