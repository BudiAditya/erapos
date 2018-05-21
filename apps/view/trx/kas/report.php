<?php
if ($outPut == "2") {
    require_once(LIBRARY . "PHPExcel.php");
    include("report.xls.php");
} else {
    include("report.web.php");
}