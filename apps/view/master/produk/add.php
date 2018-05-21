<!DOCTYPE html>
<?php
/** @var $outlets Outlet[] */
/** @var $kategoris Kategori[] */
/** @var $produks Produk[] */
/** @var $produk Produk */
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EraPOS | Tambah Produk </title>
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
                                <h2>TAMBAH PRODUK BARU</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="pad" id="infopanel"></div>
                                <form id="frm" class="form-horizontal form-label-left" action="<?php print($helper->site_url("master.produk/add")); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="cboKategoriId" class="col-sm-2  control-label">Kategori</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="cboKategoriId" name="KategoriId" required>
                                                    <option value="0" disabled selected="selected"> Pilih Kategori </option>
                                                    <?php
                                                    foreach ($kategoris as $ktg) {
                                                        if ($produk->KategoriId == $ktg->Id){
                                                            printf('<option value="%d" selected="selected">%s</option>', $ktg->Id, $ktg->Kategori);
                                                        }else {
                                                            printf('<option value="%d">%s</option>', $ktg->Id, $ktg->Kategori);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" id="checkIsForsale" name="IsForsale" value="1" <?php print($produk->IsForsale == 1 ? 'checked = "checked"' : '');?>> Produk untuk dijual
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" id="checkIsAktif" name="IsAktif" value="1" <?php print($produk->IsAktif == 1 ? 'checked = "checked"' : '');?>> Produk Aktif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSku" class="col-sm-2  control-label">S K U</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="txtSku" name="Sku" value="<?php print($produk->Sku);?>" placeholder="S K U" required>
                                                <input type="hidden" id="txtId" value="0">
                                                <input type="hidden" id="txtOutletId" name="OutletId" value="<?php print($produk->OutletId);?>">
                                            </div>
                                            <label for="txtBarcode" class="col-sm-2  control-label">Bar Code</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="txtBarcode" name="Barcode" value="<?php print($produk->Barcode);?>" placeholder="Bar Code" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtNama" class="col-sm-2  control-label">Nama Produk</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="txtNama" name="Nama" placeholder="Nama Produk" value="<?php print($produk->Nama);?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSatuan" class="col-sm-2 control-label">Satuan</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="txtSatuan" name="Satuan" value="<?php print($produk->Satuan);?>" placeholder="Satuan" required>
                                            </div>
                                            <label for="txtHrgBeli" class="col-sm-2  control-label">Hrg Beli</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control num" id="txtHrgBeli" placeholder="Harga Beli" name="HrgBeli" value="<?php print(number_format($produk->HrgBeli,0));?>" required style="text-align: right;">
                                            </div>
                                            <label for="txtHrgJual" class="col-sm-2  control-label">Hrg Jual</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control num" id="txtHrgBeli" placeholder="Harga Jual" name="HrgJual" value="<?php print(number_format($produk->HrgJual,0));?>" required style="text-align: right;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSatuan" class="col-sm-2 control-label">Keterangan</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="txtKeterangan" name="Keterangan" placeholder="Deskripsi Produk"><?php print($produk->Keterangan);?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" id="checkIsStock" name="IsStock" value="1" <?php print($produk->IsStock == 1 ? 'checked = "checked"' : '');?>> Kelola Stok
                                            </div>
                                        </div>
                                        <div class="form-group" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" id="checkIsModifier" name="IsModifier" value="1" <?php print($produk->IsModifier == 1 ? 'checked = "checked"' : '');?>> Produk Tambahan & Pilihan
                                            </div>
                                        </div>
                                        <div class="form-group" id="divModifier" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Produk</th>
                                                    <th>QTY</th>
                                                    <th>Harga</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="form-group" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" id="checkIsResep" name="IsResep" value="1" <?php print($produk->IsResep == 1 ? 'checked = "checked"' : '');?>> Resep (Bahan Mentah)
                                            </div>
                                        </div>
                                        <div class="form-group" id="divResep" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Produk</th>
                                                        <th>QTY</th>
                                                        <th>Harga</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="col-sm-9">
                                                <p>Foto
                                                    <?php printf('<img id="ifphoto"  src="%s" width="250" height="200"/>',$helper->site_url($produk->FPhoto)); ?>
                                                </p>
                                                <input type="file" id="iFphoto" name="FPhoto" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <div class="col-sm-9">
                                                <button type="submit" class="btn btn-primary btn-sm" id="btSave"><i class="fa fa-save"></i> Save</button>
                                                <a href="<?php print($helper->site_url("master.produk")); ?>" class="btn btn-success btn-sm">Daftar Produk</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<!-- autonumeric -->
<script src="<?php print($helper->path("public/js/auto-numeric.js"));?>"></script>
<script>
    $(document).ready( function (){
       var dta = '<?php print($produk->AvailableOutlet);?>';
       var dtx = dta.split(',');
       $("#cbmAvailableOutlet").val(dtx);
    });

    //get sku kode
    $('#cboKategoriId').change(function () {
        var kti = this.value;
        var url = "<?php print($helper->site_url("master/produk/getAutoSku/"));?>"+kti;
        $.get(url, function(data, status){
           if (data != ''){
               $('#txtSku').val(data);
               $('#txtBarcode').val(data);
           }else{
               swal("Error!", 'Gagal memperoleh Kode SKU!', "error");
           }
        });

    });

    //checking sku kode
    $('#txtSku').change(function () {
        var sku = this.value;
        var url = "<?php print($helper->site_url("master/produk/checkSku/"));?>"+sku;
        $.get(url, function(data, status){
            var dtx = data.split('|');
            if (dtx[0] == 'OK'){
                $('#txtBarcode').val(sku);
            }else{
                swal("Error!", 'Kode SKU: '+sku+ ' sudah ada!', "error");
            }
        });

    });
    //is modifier validation
    $('#checkIsModifier').change(function () {
        if ($('#checkIsModifier').is(':checked')){
            $('#divModifier').show();
        }else {
            $('#divModifier').hide();
        }
    });
    //is resep validation
    $('#checkIsResep').change(function () {
        if ($('#checkIsResep').is(':checked')){
            $('#divResep').show();
        }else {
            $('#divResep').hide();
        }
    });
    //numeric format
    $(".num").autoNumeric({mDec: '0'});
    $("#frm").submit(function(e) {
        $(".num").each(function(idx, ele){
            this.value  = $(ele).autoNumericGet({mDec: '0'});
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
    $("#iFphoto").change(function(){
        readURL(this);
    });
</script>
</body>
</html>
