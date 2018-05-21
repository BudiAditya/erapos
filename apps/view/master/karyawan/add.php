<!DOCTYPE html>
<?php
/** @var $cabangs Cabang[] */
/** @var $bagians Bagian[] */
/** @var $karyawan Karyawan */
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
        <?php }else if (isset($info)) { ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php print($info); ?>
            </div>
        <?php } ?>
        <section class="content-header">
            <h1>TAMBAH DATA KARYAWAN</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("main"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo site_url("master/karyawan"); ?>">Master</a></li>
                <li class="active">Karyawan</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-10">
                    <div class="box box-primary">
                        <!--
                        <div class="box-header with-border">
                            <h3 class="box-title">Quick Example</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="<?php print($helper->site_url("master.karyawan/add")); ?>" method="post" enctype="multipart/form-data">
                            <div class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="Fphoto" class="col-sm-2 control-label">Foto Karyawan</label>
                                        <div class="col-sm-6">
                                            <p>
                                                <?php printf('<img id="ifphoto" src="%s" width="300" height="250"/>',$helper->site_url($karyawan->Fphoto)); ?>
                                            </p>
                                            <input type="file" id="Fphoto" name="Fphoto" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="CabangId" class="col-sm-2 control-label">Cabang</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="CabangId" name="CabangId" required>
                                                <option value="0" disabled selected="selected">Pilih Cabang</option>
                                                <?php
                                                foreach($cabangs as $cabang){
                                                    if($karyawan->CabangId == $cabang->Id){
                                                        printf("<option value='%d' selected='selected'>%s - %s</option>", $cabang->Id, $cabang->Kode,$cabang->Cabang);
                                                    }else {
                                                        printf("<option value='%d'>%s - %s</option>", $cabang->Id, $cabang->Kode,$cabang->Cabang);
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Nik" class="col-sm-2 control-label">N I K</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Nik" name="Nik" placeholder="N I K" value="<?php print($karyawan->Nik);?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Nama" class="col-sm-2 control-label">Nama Lengkap</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Nama Lengkap" value="<?php print($karyawan->Nama);?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="NmPanggilan" class="col-sm-2 control-label">Panggilan</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="NmPanggilan" name="NmPanggilan" placeholder="Nama Panggilan" value="<?php print($karyawan->NmPanggilan);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="BagianId" class="col-sm-2 control-label">Bagian</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="BagianId" name="BagianId" required>
                                                <option value="0" disabled selected="selected">Pilih Bagian</option>
                                                <?php
                                                foreach($bagians as $bagian){
                                                    if($karyawan->BagianId == $bagian->Id){
                                                        printf("<option value='%d' selected='selected'>%s - %s</option>", $bagian->Id, $bagian->Kode, $bagian->NmBagian);
                                                    }else {
                                                        printf("<option value='%d'>%s - %s</option>", $cabang->Id, $bagian->Kode, $bagian->NmBagian);
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Jabatan" class="col-sm-2 control-label">Jabatan</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="Jabatan" name="Jabatan" required>
                                                <option value="0" disabled selected="selected">Pilih Jabatan</option>
                                                <option value="STF" <?php ($karyawan->Jabatan == "STF" ? print('selected = "selected"'):'');?>>Staf</option>
                                                <option value="SPV" <?php ($karyawan->Jabatan == "SPV" ? print('selected = "selected"'):'');?>>Supervisor</option>
                                                <option value="MGR" <?php ($karyawan->Jabatan == "MGR" ? print('selected = "selected"'):'');?>>Manager</option>
                                                <option value="DIR" <?php ($karyawan->Jabatan == "DIR" ? print('selected = "selected"'):'');?>>Direktur</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Alamat" class="col-sm-2 control-label">Alamat</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Alamat" name="Alamat" placeholder="Alamat Tinggal" value="<?php print($karyawan->Alamat);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Handphone" class="col-sm-2 control-label">Handphone</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="Handphone" name="Handphone" placeholder="Nomor Handphone" value="<?php print($karyawan->Handphone);?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Status" class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-6">
                                            <input type="checkbox" class="minimal" id="Status" name="Status" value="1" <?php print($karyawan->Status == 1 ? 'checked' : '');?>>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                                    <a href="<?php print($helper->site_url("master.karyawan")); ?>" class="btn btn-success btn-sm">Daftar Karyawan</a>
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
<script>
    $(document).ready(function() {
        $("#CabangId").change(function () {
            var url = "<?php print($helper->site_url("master.karyawan/autoNik/")); ?>" + this.value;
            $.get(url, function (data) {
                $("#Nik").val(data);
            });
        });
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

    $("#Fphoto").change(function(){
        readURL(this);
    });
</script>
</body>
</html>
