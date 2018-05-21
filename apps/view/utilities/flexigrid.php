<!DOCTYPE HTML>
<html>
<head>
	<title>KSP - <?php print($settings["title"]); ?></title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php print($helper->path("public/css/jquery-ui.css")); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print($helper->path("public/css/flexigrid.css")); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print($helper->path("public/css/common.css")); ?>" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php print($helper->path("assets/vendor/bootstrap/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?php print($helper->path("assets/vendor/metisMenu/metisMenu.min.css"));?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php print($helper->path("assets/dist/css/sb-admin-2.css"));?>" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?php print($helper->path("assets/vendor/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div id="wrapper">
    <?php include(VIEW . "main/menu.php"); ?>
    <div id="page-wrapper">
        <div class="subTitle bold" style="margin-bottom: 10px;"><?php print($settings["title"]); ?></div>
        <table id="searchTable">
            <!-- Bikin Dummy Rows dulu agar Flexigridnya bisa pilih2 kolom dan pas ganti halaman di disable dulu... -->
            <?php
        //	$max = $settings["recordPerPage"];
        //	for ($i = 0; $i < $max; $i++) {
        //		print('<tr><td>&nbsp;</td></tr>');
        //	}
            ?>
        </table>
    </div>
</div>

<!-- jQuery -->
<script src="<?php print($helper->path("assets/vendor/jquery/jquery.min.js"));?>"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php print($helper->path("assets/vendor/bootstrap/js/bootstrap.min.js"));?>"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="<?php print($helper->path("assets/vendor/metisMenu/metisMenu.min.js"));?>"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php print($helper->path("assets/dist/js/sb-admin-2.js"));?>"></script>
<script type="text/javascript" src="<?php print($helper->path("public/js/jquery-ui.custom.min.js")); ?>"></script>
<script type="text/javascript" src="<?php print($helper->path("public/js/flexigrid.js")); ?>"></script>
<script type="text/javascript" src="<?php print($helper->path("public/js/rsh.js")); ?>"></script>
<script type="text/javascript" src="<?php print($helper->path("public/js/common.js")); ?>"></script>
<script type="text/javascript">
    // Ini bagian DHTML History agar Ajax bisa masuk history (masih bego karena masih pake # belom #! kaya di twitter)
    window.dhtmlHistory.create({
        toJSON: function(obj) { return JSON.stringify(obj); }
        , fromJSON: function(str) { return JSON.parse(str); }
    });
    window.onload = function() {
        dhtmlHistory.initialize();
        dhtmlHistory.addListener(historyChanged);
    };

    var flexigrid;
    var eventHandlers = eval(<?php print(json_encode($eventHandlers)); ?>);
    var flagFromHistory = false;

    $(document).ready(function() {
        flexigrid = $("#searchTable").flexigrid(<?php print($scriptFg); ?>);
        var hash = dhtmlHistory.getCurrentHash();
        if(hash == "") {
            // First Load of flexigird search page...
            flexigrid.flexReload();
        } else {
            // User return from history...
            ReloadFlexigridFromHash(hash);
        }
    });

    function ReloadFlexigridFromHash(hash) {
        if (hash == "") {
            return;
        }
        flagFromHistory = true;
        var searchUrl = "<?php print($searchUrl); ?>?" + hash;
        flexigrid.flexSimulateSearch();
        $.get(searchUrl, { }, function(data) {
            flexigrid.flexAddData(data);
        }, "json");
    }

    function GenerateUrl(eventHandler, selectedItems) {
        var url = eventHandler.Url;
        var item = null;

        if (eventHandler.ReqId == 1) {
            item = selectedItems[0];
            url = url.replace("%s", item.id.substr(3));
        } else {
            var max = selectedItems.length;
            url = url.replace("%s", "");
            url += "?";

            for (var i = 0; i < max; i++) {
                item = selectedItems[i];
                url += "&id[]=" + item.id.substr(3);
            }
        }

        return url;
    }

    function historyChanged(newHash, historyData) {
        ReloadFlexigridFromHash(newHash);
    }

    function searchTable_Success() {
        if (flagFromHistory) {
            flagFromHistory = false;
            return;
        }
        // Retrieve all data which send to server in our local history...
        var params = {
            page: this.page
            , rp: this.rp
            , sortname: this.sortname
            , sortorder: this.sortorder
            , query: this.query
            , qtype: this.qtype
            , condition: this.condition
            , query2: this.query2
            , qtype2: this.qtype2
        };
        var hashCode = $.param(params);
        dhtmlHistory.add(hashCode, null);
    }

    // DAMN.... I HATE IT BUT... Because this one for all and use template I unable to perform specific event handler...
    function fgButton_Click(commandText, grid, idx) {
        var items = $("#searchTable .trSelected");
        var eventHandler = eventHandlers[idx];
        var confirmMessage;

        if (eventHandler.ReqId == 0) {
            // Tidak perlu ID maka langsung ke halaman yang diminta jika tidak ada confirm secara explicit
            confirmMessage = eventHandler.Confirm;
            if (confirmMessage == "" || confirmMessage == null) {
                window.location = eventHandler.Url;
            } else {
                if (confirm(confirmMessage)) {
                    window.location = eventHandler.Url;
                }
            }
            return;
        } else if (eventHandler.ReqId == 1) {
            // Harus TEPAT 1 id yang dipilih
            if (items.length != 1) {
                if (eventHandler.Error != null) {
                    alert(eventHandler.Error);
                } else {
                    alert("Maaf anda belum memilih data !\nSilahkan memilih data sebelum melakukan proses yang diinginkan");
                }
                return;
            }
        } else if (eventHandler.ReqId > 1) {
            // At least 1 (boleh 1 boleh lebih)
            if (items.length == 0) {
                if (eventHandler.Error != null) {
                    alert(eventHandler.Error);
                } else {
                    alert("Maaf anda belum memilih data !\nSilahkan memilih data sebelum melakukan proses yang diinginkan");
                }
                return;
            }
        } else {
            // DAFUQ.... Settings error ??
            alert("Invalid Server Settings: ReqId = " + eventHandler.ReqId + "\n\nHARAP HUBUNGI SYSTEM ADMIN ANDA !");
            return;
        }

        confirmMessage = eventHandler.Confirm != null ? eventHandler.Confirm : "Apakah anda yakin mau memproses data yang dipilih?\nKlik 'OK' untuk melanjutkan prosedur.";
        if (confirmMessage != "") {
            if (confirm(confirmMessage)) {
                window.open(GenerateUrl(eventHandler, items), eventHandler.Target);
            }
        } else {
            window.open(GenerateUrl(eventHandler, items), eventHandler.Target);
        }
    }
</script>

</body>

</html>
