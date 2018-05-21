<!DOCTYPE html>
<?php
/** @var $company Company */
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KSP | Master Data</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/bower_components/bootstrap/dist/css/bootstrap.min.css"));?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/bower_components/font-awesome/css/font-awesome.min.css"));?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/bower_components/Ionicons/css/ionicons.min.css"));?>">
    <link rel="stylesheet" href="<?php print($helper->path("assets/dist/css/AdminLTE.min.css"));?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"));?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/dist/css/skins/_all-skins.min.css"));?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- main menu -->
    <?php include(VIEW . "main/menu.php"); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php if (isset($error)) { ?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php print($error); ?>
            </div>
        <?php }
        if ($info != null) { ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php print($info); ?>
            </div>
        <?php } ?>
        <section class="content-header">
            <h1>DATA PERUSAHAAN</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("main"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo site_url("master/company"); ?>">Master</a></li>
                <li class="active">Perusahaan</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <!--
                        <div class="box-header with-border">
                            <h3 class="box-title">Quick Example</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="<?php print($helper->site_url("master.company")); ?>" method="post" enctype="multipart/form-data">
                            <div class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="Flogo" class="col-sm-2 control-label">File Logo</label>
                                        <div class="col-sm-6">
                                        <p>
                                            <?php printf('<img id="ifphoto" src="%s" width="300" height="200"/>',$helper->site_url($company->Flogo)); ?>
                                        </p>
                                            <input type="file" id="Flogo" name="Flogo" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="EntityCd" class="col-sm-2 control-label">Kode</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="EntityCd" name="EntityCd" placeholder="Kode" value="<?php print($company->EntityCd);?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="CompanyName" class="col-sm-2 control-label">Nama Usaha</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="CompanyName" name="CompanyName" placeholder="Nama Perusahaan" value="<?php print($company->CompanyName);?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Address" class="col-sm-2 control-label">Alamat</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Address" name="Address" placeholder="Alamat" value="<?php print($company->Address);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="City" class="col-sm-2 control-label">Kota</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="City" name="City" placeholder="Kota" value="<?php print($company->City);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Province" class="col-sm-2 control-label">Propinsi</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Province" name="Province" placeholder="Propinsi" value="<?php print($company->Province);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Telephone" class="col-sm-2 control-label">Telephone</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Telephone" name="Telephone" placeholder="Telephone" value="<?php print($company->Telephone);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="PersonInCharge" class="col-sm-2 control-label">P I C</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="PersonInCharge" name="PersonInCharge" placeholder="Penanggung Jawab" value="<?php print($company->PersonInCharge);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="StartDate" class="col-sm-2 control-label">Mulai Tgl</label>
                                        <div class="col-sm-6">
                                           <input type="text" class="form-control" id="StartDate" name="StartDate" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask required placeholder="Tanggal" value="<?php print($company->FormatStartDate(JS_DATE));?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">U P D A T E</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- footer here -->
    <?php include(VIEW . "main/footer.php"); ?>
    <!-- sidebar comtrol -->
    <?php include(VIEW . "main/sidebar-ctl.php"); ?>
</div>
<!-- ./wrapper -->
<!-- /#wrapper -->
<!-- jQuery 3 -->
<script src="<?php print($helper->path("assets/bower_components/jquery/dist/jquery.min.js"));?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php print($helper->path("assets/bower_components/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
<!-- FastClick -->
<script src="<?php print($helper->path("assets/bower_components/fastclick/lib/fastclick.js"));?>"></script>
<!-- AdminLTE App -->
<script src="<?php print($helper->path("assets/dist/js/adminlte.min.js"));?>"></script>
<!-- Sparkline -->
<script src="<?php print($helper->path("assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"));?>"></script>
<!-- SlimScroll -->
<script src="<?php print($helper->path("assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"));?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php print($helper->path("assets/dist/js/pages/dashboard2.js"));?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php print($helper->path("assets/dist/js/demo.js"));?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php print($helper->path("assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"));?>"></script>
<script>
    $(function () {
        //Datemask dd/mm/yyyy
        //$('#tgl_mulai').inputmask('dd-mm-yyyy', {'placeholder': 'dd/mm/yyyy'})
        //Date picker
        $('#StartDate').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            keyboardNavigation : true
        })
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

    $("#Flogo").change(function(){
        readURL(this);
    });
</script>
</body>
</html>
