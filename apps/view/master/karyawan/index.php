<!DOCTYPE html>
<?php
/** @var $karyawans Karyawan[] */
$badd = base_url('public/images/button/').'add.png';
$bedit = base_url('public/images/button/').'edit.png';
$bdelete = base_url('public/images/button/').'delete.png';
?>
<html>
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
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"));?>">
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/buttons.dataTables.min.css"));?>"/>
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/select.dataTables.min.css"));?>"/>
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/responsive.dataTables.min.css"));?>"/>
    <!-- jConfirm -->
    <link rel="stylesheet" href="<?php print($helper->path("assets/cdn/css/jquery-confirm.min.css"));?>">
    <!-- Theme style -->
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
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        table.dataTable tbody>tr.selected,
        table.dataTable tbody>tr>.selected {
            background-color: #A2D3F6;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- main menu -->
    <?php include(VIEW . "main/menu.php"); ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- alert & info -->
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
          <h1>DAFTAR KARYAWAN</h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo site_url("main"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Master</a></li>
            <li class="active">Karyawan</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
              <!--
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div>
              -->
                <!-- /.box-header -->
                <div class="box-body">
                    <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped" id="tableKaryawan">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Cabang/Unit</th>
                            <th>Bagian</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $dta = null;
                        foreach ($karyawans as $karyawan) {
                            print("<tr>");
                            printf("<td>%d</td>",$karyawan->Id);
                            printf("<td>%s</td>",$karyawan->Nik);
                            printf("<td>%s</td>",$karyawan->Nama);
                            printf("<td>%s</td>",$karyawan->CabangCd);
                            printf("<td>%s</td>",$karyawan->BagianKode);
                            printf("<td>%s</td>",$karyawan->Jabatan);
                            printf("<td>%s</td>",$karyawan->Status == 1 ? 'Aktif' : 'Non-Aktif');
                            print("</tr>");
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
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

<!-- jQuery 3 -->
<script src="<?php print($helper->path("assets/bower_components/jquery/dist/jquery.min.js"));?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php print($helper->path("assets/bower_components/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/bower_components/datatables.net/js/jquery.dataTables.min.js"));?>"></script>
<!-- DataTables -->
<script src="<?php print($helper->path("assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/vendor/datatables-editor/dataTables.altEditor.free.js"));?>"></script>
<!-- Datatables plugins -->
<script src="<?php print($helper->path("assets/cdn/js/dataTables.buttons.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/cdn/js/dataTables.select.min.js"));?>"></script>
<script src="<?php print($helper->path("assets/cdn/js/dataTables.responsive.min.js"));?>"></script>
<!-- SlimScroll -->
<script src="<?php print($helper->path("assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"));?>"></script>
<!-- FastClick -->
<script src="<?php print($helper->path("assets/bower_components/fastclick/lib/fastclick.js"));?>"></script>
<!-- AdminLTE App -->
<script src="<?php print($helper->path("assets/dist/js/adminlte.min.js"));?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php print($helper->path("assets/dist/js/demo.js"));?>"></script>
<!-- jConfirm -->
<script src="<?php print($helper->path("assets/cdn/js/jquery-confirm.min.js"));?>"></script>
<!-- actions -->
<script>
    $(document).ready(function() {
        var myTable;
        myTable = $('#tableKaryawan').DataTable({
            "sPaginationType": "full_numbers",
            dom: 'Bfrtip',        // Needs button container
            select: 'single',
            responsive: true,
            altEditor: true,     // Enable altEditor
            buttons: [{
                    text: '<?php printf('<img src="%s" alt="Add" title="Tambah Data" style="cursor: pointer"/>',$badd);?>',
                    //name: 'add'        // do not change name
                    name: 'addNew',
                    action: function ( e, dt, node, config ) {
                        location.href = '<?php print($helper->site_url("master/karyawan/add"));?>';
                    }
                },
                {
                    extend: 'selected', // Bind to Selected row
                    text: '<?php printf('<img src="%s" alt="Edit" title="Ubah Data" style="cursor: pointer"/>',$bedit);?>',
                    //name: 'edit'        // do not change name
                    name: 'editData',
                    action: function ( e, dt, node, config ) {
                        var dtx = dt.rows({selected:  true}).data();
                        var dts = Number(dtx[0][0]);
                        if (dts > 0){
                            $.confirm({
                                title: 'Konfirmasi',
                                content: 'Ubah data karyawan: '+dtx[0][2]+' ('+dtx[0][1]+')?',
                                buttons: {
                                    confirm: function () {
                                        location.href = '<?php print($helper->site_url("master/karyawan/edit/"));?>'+dts;
                                    },
                                    cancel: function () {
                                        //$.alert('Pengeditan batal!');
                                    }
                                }
                            });
                        }
                    }
                },
                {
                    extend: 'selected', // Bind to Selected row
                    text: '<?php printf('<img src="%s" alt="Delete" title="Hapus Data" style="cursor: pointer"/>',$bdelete);?>',
                    //name: 'delete'      // do not change name
                    name: 'deleteData',
                    action: function ( e, dt, node, config ) {
                        var dtx = dt.rows({selected:  true}).data();
                        var dts = Number(dtx[0][0]);
                        if (dts > 0) {
                            $.confirm({
                                title: 'Konfirmasi',
                                content: 'Hapus data karyawan: '+dtx[0][2]+' ('+dtx[0][1]+')?',
                                buttons: {
                                    confirm: function () {
                                        var url = '<?php print($helper->site_url("master/karyawan/delete/"));?>' + dts;
                                        $.get(url, function(data, status) {
                                            $.dialog({
                                                title: 'Info',
                                                content: data,
                                            });
                                            location.reload();
                                        });
                                    },
                                    cancel: function () {
                                        //$.alert('Penghapusan batal!');
                                    }
                                }
                            });
                        }
                    }
                }
            ]

        });
        //button control action

    });
</script>
</body>
</html>
