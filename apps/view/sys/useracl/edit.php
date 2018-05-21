<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KSP | User Access Control</title>
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
            <h1>PENGATURAN HAK AKSES - USER: <?php print($userdata->UserId);?></h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("main"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?php echo site_url("sys/useradmin"); ?>">System</a></li>
                <li class="active">User ACL</li>
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
                        <form id="frm" action="<?php printf($helper->site_url('sys.useracl/edit/%s'), $userdata->UserUid); ?>" method="post">
                            <div class="box-body">
                              <div class="table-responsive">
                                <?php
                                $m1 = "";
                                $akses = null;
                                foreach ($resources as $menu) {
                                    if ($m1 != $menu->MenuName) {
                                        if ($m1 != "") {
                                            print('</table>');
                                            print('<br>');
                                        }

                                        $m1 = $menu->MenuName;
                                        printf('<p><b>Modul %s</b></p>', $menu->MenuName);
                                        print('<table class="table table-bordered table-hover table-striped">');
                                        print('<tr align="center" "><th>No.</th><th>Nama Menu</th><th>Tambah</th><th>Ubah</th><th>Hapus</th><th>Lihat</th><th>Cetak</th><th>Semua</th></tr>');
                                    }

                                    if (isset($hak[$menu->ResourceId])) {
                                        $akses = $hak[$menu->ResourceId];
                                    } else {
                                        $akses = null;
                                    }

                                    print('<tr>');
                                    printf('<td align="center">%d</td>', $menu->ResourceSeq);
                                    printf('<td >%s</td>', $menu->ResourceName);
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|1" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "1") !== false) ? 'checked="checked"' : '');
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|2" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "2") !== false) ? 'checked="checked"' : '');
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|3" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "3") !== false) ? 'checked="checked"' : '');
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|4" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "4") !== false) ? 'checked="checked"' : '');
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|5" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "5") !== false) ? 'checked="checked"' : '');
                                    //printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|6" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "6") !== false) ? 'checked="checked"' : '');
                                    //printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|7" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "7") !== false) ? 'checked="checked"' : '');
                                    //printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|8" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "8") !== false) ? 'checked="checked"' : '');
                                    printf('<td align="center"><input type="checkbox" name="hakakses[]" value="%s|9" %s /></td>', $menu->ResourceId, ($akses != null && strpos($akses->Rights, "9") !== false) ? 'checked="checked"' : '');
                                    print('</tr>');
                                }

                                // Hmm spt biasa yang terakhir tidak ter print untuk tag close nya
                                if ($m1 != "") {
                                    print("</table>");
                                }
                                ?>
                              </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                <a href="<?php print($helper->site_url("sys.useradmin")); ?>" class="btn btn-success btn-sm">Daftar User System</a>
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
</body>
</html>
