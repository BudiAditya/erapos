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
    <title>EraPOS | Ubah Produk </title>
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
                                <h2>UBAH DATA PRODUK</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="pad" id="infopanel"></div>
                                <p>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btAdd" name="btAdd"><i class="fa fa-plus"></i> Tambah Produk Baru</button>
                                </p>
                                <form id="frm" class="form-horizontal form-label-left" action="<?php print($helper->site_url("master.produk/edit/".$produk->Id)); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="cboKategoriId" class="col-sm-2  control-label">Kategori</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" id="cboKategoriId" name="KategoriId" readonly>
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
                                                <input type="hidden" id="txtId" name="Id" value="<?php print($produk->Id);?>">
                                                <input type="hidden" id="txtOutletId" name="OutletId" value="<?php print($produk->OutletId);?>">
                                            </div>
                                            <label for="txtBarcode" class="col-sm-2  control-label">Bar Code</label>
                                            <div class="col-md-4">
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
                                                <input type="text" class="form-control num" id="txtHrgJual" placeholder="Harga Jual" name="HrgJual" value="<?php print(number_format($produk->HrgJual,0));?>" required style="text-align: right;">
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
                                        <div class="form-group">
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-6">
                                                <input type="checkbox" id="checkIsModifier" name="IsModifier" value="1" <?php print($produk->IsModifier == 1 ? 'checked = "checked"' : '');?>> Produk Tambahan & Pilihan
                                            </div>
                                            <div id="divBtMod" class="col-sm-4 pull-right" hidden>
                                                <button type="button" class="btn btn-primary btn-xs" id="btAddModifier" name="btAddModifier"><i class="fa fa-plus"></i> Add Modifier</button>
                                            </div>
                                        </div>
                                        <div class="form-group" id="divModifier" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                            <table class="table" id="tableModifier">
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
                                        <div class="form-group">
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-6">
                                                <input type="checkbox" id="checkIsResep" name="IsResep" value="1" <?php print($produk->IsResep == 1 ? 'checked = "checked"' : '');?>> Resep (Bahan Baku/Mentah)
                                            </div>
                                            <div id="divBtRes" class="col-sm-4 pull-right" hidden>
                                                <button type="button" class="btn btn-primary btn-xs" id="btAddResep" name="btAddResep"><i class="fa fa-plus"></i> Add Resep</button>
                                            </div>
                                        </div>
                                        <div class="form-group" id="divResep" hidden>
                                            <label class="col-sm-2  control-label"></label>
                                            <div class="col-sm-10">
                                                <table class="table" id="tableResep">
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
                                            <div class="col-sm-12">
                                                <?php printf('<img id="ifphoto"  src="%s" width="250" height="200"/>',$helper->site_url($produk->FPhoto)); ?>
                                                <input type="file" id="iFphoto" name="FPhoto" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary btn-sm" id="btSave"><i class="fa fa-save"></i> Update</button>
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
        <div id="modalProduk" class="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><div id="frmTitle">Data Produk</div></h4>
                    </div>
                    <!--modal header-->
                    <div class="modal-body">
                        <div class="pad" id="infopanel"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="cbmProduk" class="col-sm-3  control-label" id="lblProduk">Produk</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="cbmProduk">
                                        <option value="0" disabled selected="selected"> Pilih Produk </option>
                                    </select>
                                    <input type="hidden" id="txmSku" value="">
                                    <input type="hidden" id="txmId" value="0">
                                    <input type="hidden" id="crudMethod" value="">
                                    <input type="hidden" id="kdProses" value="">
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="txmQty" class="col-sm-3  control-label">QTY</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="txmQty" placeholder="Qty" style="text-align: right;" value="1">
                                </div>
                                <label for="txmHarga" class="col-sm-2  control-label">Harga</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="txmHarga" placeholder="Harga" value="0" style="text-align: right;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3  control-label"></label>
                                <div class="col-sm-9">
                                    <button type="button" class="btn btn-primary btn-sm" id="btmSave"><i class="fa fa-save"></i> Save</button>
                                    <button type="button" class="btn btn-default btn-sm" id="btmCancel"><i class="fa fa-close"></i> Cancel</button>
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
<!-- autonumeric -->
<script src="<?php print($helper->path("public/js/auto-numeric.js"));?>"></script>
<script>
    $(document).ready( function (){
        //tampilkan data table modifier
        $('#tableModifier').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": false,
            "responsive": false,
            "autoWidth": false,
            "pageLength": 5,
            "ajax": {
                "url": "<?php print($helper->site_url("master/produk/getJsonProdukModifier/".$produk->Sku));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                //{ "data": "sku" },
                { "data": "nama" },
                { "data": "qty" },
                //{ "data": "satuan" },
                { "data": "harga" },
                { "data": "button" }
            ]
        });
        //tampilkan data table modifier
        $('#tableResep').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": false,
            "responsive": false,
            "autoWidth": false,
            "pageLength": 5,
            "ajax": {
                "url": "<?php print($helper->site_url("master/produk/getJsonProdukResep/".$produk->Sku));?>",
                "type": "POST"
            },
            "columns": [
                { "data": "urutan" },
                //{ "data": "sku" },
                { "data": "nama" },
                { "data": "qty" },
                //{ "data": "satuan" },
                { "data": "harga" },
                { "data": "button" }
            ]
        });
        //isi multiselect value
        var dta = '<?php print($produk->AvailableOutlet);?>';
        var dtx = dta.split(',');
        $("#cbmAvailableOutlet").val(dtx);

        //modifier checked
        if ($('#checkIsModifier').is(':checked')){
            $('#divModifier').show();
            $('#divBtMod').show();
        }else {
            $('#divModifier').hide();
            $('#divBtMod').hide();
        }
        //resep checked
        if ($('#checkIsResep').is(':checked')){
            $('#divResep').show();
            $('#divBtRes').show();
        }else {
            $('#divResep').hide();
            $('#divBtRes').hide();
        }

        $("#cbmProduk").change(function (e) {
           var dta = this.value.split('|');
           var prs = $("#kdProses").val();
           $("#txmSku").val(dta[0]);
           $("#txmQty").val(1);
           if (prs == 'M'){
               $("#txmHarga").val(dta[2]);
           }else if (prs == 'R'){
               $("#txmHarga").val(dta[1]);
           }
        });
    });

    //proses tambah data
    $('#btAdd').click(function (e) {
        var urx = "<?php print($helper->site_url("master/produk/add"));?>";
        location.href = urx;
    });

    //is modifier validation
    $('#checkIsModifier').change(function () {
        if ($('#checkIsModifier').is(':checked')){
            var kti = $("#cboKategoriId").val();
            if (kti == 5) {
                $('#divModifier').show();
                $('#divBtMod').show();
            }else{
                swal("Error!", "Modifier hanya untuk -Produk Paket-", "error");
                $('#checkIsModifier').prop('checked', false);
            }
        }else {
            $('#divModifier').hide();
            $('#divBtMod').hide();
        }
    });
    //is resep validation
    $('#checkIsResep').change(function () {
        if ($('#checkIsResep').is(':checked')){
            var kti = $("#cboKategoriId").val();
            if (kti > 1 && kti < 5) {
                $('#divResep').show();
                $('#divBtRes').show();
            }else{
                swal("Error!", "Resep hanya untuk -Produk Dijual Non-Paket-", "error");
                $('#checkIsResep').prop('checked', false);
            }
        }else {
            $('#divResep').hide();
            $('#divBtRes').hide();
        }
    });

    //proses hapus data modifier
    $(document).on( "click",".btmDelete", function() {
        var id_produk = $(this).attr("id_produk");
        var psku = $(this).attr("sku");
        var pnama = $(this).attr("nama");
        swal({
                title: "Hapus Data",
                text: "Hapus Data Modifier : "+pnama+" ("+psku+") ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true },
            function(){
                var idvalue = {id: id_produk};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("master/produk/delmodifier"));?>",
                        type: "POST",
                        data : idvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data modifier!');
                                var table = $('#tableModifier').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal hapus data modifier, Error : "+data.error,"error");
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            swal("Error!", textStatus, "error");
                        }
                    });
            });
    });

    //proses hapus data resep
    $(document).on( "click",".btrDelete", function() {
        var id_produk = $(this).attr("id_produk");
        var psku = $(this).attr("sku");
        var pnama = $(this).attr("nama");
        swal({
                title: "Hapus Data",
                text: "Hapus Data Resep : "+pnama+" ("+psku+") ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true },
            function(){
                var idvalue = {id: id_produk};
                $.ajax(
                    {
                        url : "<?php print($helper->site_url("master/produk/delresep"));?>",
                        type: "POST",
                        data : idvalue,
                        success: function(data, textStatus, jqXHR)
                        {
                            var data = jQuery.parseJSON(data);
                            if(data.result == 1){
                                $.notify('Berhasil hapus data resep!');
                                var table = $('#tableResep').DataTable();
                                table.ajax.reload( null, false );
                            }else{
                                swal("Error","Gagal hapus data resep, Error : "+data.error,"error");
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            swal("Error!", textStatus, "error");
                        }
                    });
            });
    });

    //proses simpan data modifier atau resep
    $(document).on("click","#btmSave",function(){
        var id_produk = $("#txmId").val();
        var sku_utama = $("#txtSku").val();
        var sku = $("#txmSku").val();
        var qty = $("#txmQty").val();
        var harga = $("#txmHarga").val();
        var proses = $("#kdProses").val();
        var crud = $("#crudMethod").val();
        var urx = null;
        //validasi
        if (sku_utama == '' || sku_utama == null ){
            swal("Warning","Produk Induk tidak boleh kosong!","warning");
            $("#txtNama").focus();
            return;
        }
        if (sku == '' || sku == null ){
            swal("Warning","Sku Produk harus diisi!","warning");
            $("#txmSku").focus();
            return;
        }
        if (qty == 0 || qty == null ){
            swal("Warning","QTY harus diisi!","warning");
            $("#txmQty").focus();
            return;
        }
        if (proses == 'M') {
            if (crud == 'N') {
                urx = "<?php print($helper->site_url("master/produk/addmodifier"));?>";
            } else if (crud == 'E') {
                urx = "<?php print($helper->site_url("master/produk/editmodifier"));?>"
            }
        }else if (proses == 'R'){
            if (crud == 'N') {
                urx = "<?php print($helper->site_url("master/produk/addresep"));?>";
            } else if (crud == 'E') {
                urx = "<?php print($helper->site_url("master/produk/editresep"));?>"
            }
        }

        var dvalue = {
            Id: id_produk,
            SkuUtama: sku_utama,
            Sku: sku,
            Qty: qty,
            Harga: harga,
            KdProse: proses,
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
                            if (proses == 'M') {
                                var table = $('#tableModifier').DataTable();
                            }else if(proses = 'R'){
                                var table = $('#tableResep').DataTable();
                            }
                            table.ajax.reload( null, false );
                            $("#kdProses").val('');
                            $("#cbmProduk").val('');
                            $("#txmSku").val('');
                            $("#txmSatuan").val('');
                            $("#txmQty").val('0');
                            $("#txmHarga").val('0');
                            $("#modalProduk").modal("hide");
                        }else{
                            swal("Error","Gagal simpan data.., Error : "+data.error,"error");
                        }
                    }else if(data.crud == 'E'){
                        if(data.result == 1){
                            $.notify('Successfull update data');
                            if (proses == 'M') {
                                var table = $('#tableModifier').DataTable();
                            }else if(proses = 'R'){
                                var table = $('#tableResep').DataTable();
                            }
                            table.ajax.reload( null, false );
                            $("#kdProses").val('');
                            $("#cbmProduk").val('');
                            $("#txmSku").val('');
                            $("#txmSatuan").val('');
                            $("#txmQty").val('0');
                            $("#txmHarga").val('0');
                            $("#modalProduk").modal("hide");
                        }else{
                            swal("Error","Gagal update data .., Error : "+data.error,"error");
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

    //is have modifier
    $('#btAddModifier').click(function (e) {
        var url = "<?php print($helper->site_url("master/produk/promodifierlist"));?>";
        $("#frmTitle").text('Tambah Modifier');
        $("#lblProduk").text('Produk Modifier');
        $("#kdProses").val('M');
        $("#cbmProduk").load(url);
        $("#cbmProduk").val('0');
        $("#txmSku").val('');
        $("#txmSatuan").val('');
        $("#txmQty").val('0');
        $("#txmHarga").val('0');
        $("#crudMethod").val('N');
        $("#modalProduk").modal("show");
    });

    //cancel add modifier or resep
    $('#btmCancel').click(function (e) {
        $("#kdProses").val('');
        $("#cbmProduk").val('0');
        $("#txmSku").val('');
        $("#txmSatuan").val('');
        $("#txmQty").val('0');
        $("#txmHarga").val('0');
        $("#crudMethod").val('');
        $("#modalProduk").modal("hide");
    });

    //is have recipt
    $('#btAddResep').click(function (e) {
        var url = "<?php print($helper->site_url("master/produk/probahanlist"));?>";
        $("#frmTitle").text('Tambah Resep');
        $("#lblProduk").text('Produk Resep');
        $("#kdProses").val('R');
        $("#cbmProduk").load(url);
        $("#cbmProduk").val('0');
        $("#txmSku").val('');
        $("#txmSatuan").val('');
        $("#txmQty").val('0');
        $("#crudMethod").val('N');
        $("#txmHarga").val('0');
        $("#modalProduk").modal("show");
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
