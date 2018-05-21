<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Eraditya Inc
 * Date: 16/01/15
 * Time: 7:42
 * To change this template use File | Settings | File Templates.
 */
$phpExcel = new PHPExcel();
$headers = array(
    'Content-Type: application/vnd.ms-excel'
, 'Content-Disposition: attachment;filename="print-cashbook-transaction.xls"'
, 'Cache-Control: max-age=0'
);
$writer = new PHPExcel_Writer_Excel5($phpExcel);
// Excel MetaData
$phpExcel->getProperties()->setCreator("Erasystem Infotama Inc (c) Budi Aditya")->setTitle("Print Laporan")->setCompany("Erasystem Infotama Inc");
$sheet = $phpExcel->getActiveSheet();
$sheet->setTitle("Rekapitulasi Transaksi Kas");
//helper for styling
$center = array("alignment" => array("horizontal" => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
$right = array("alignment" => array("horizontal" => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
$allBorders = array("borders" => array("allborders" => array("style" => PHPExcel_Style_Border::BORDER_THIN)));
$idrFormat = array("numberformat" => array("code" => '_([$-421]* #,##0_);_([$-421]* (#,##0);_([$-421]* "-"??_);_(@_)'));
// OK mari kita bikin ini cuma bisa di read-only
//$password = "" . time();
//$sheet->getProtection()->setSheet(true);
//$sheet->getProtection()->setPassword($password);

// FORCE Custom Margin for continous form
/*
$sheet->getPageMargins()->setTop(0)
    ->setRight(0.2)
    ->setBottom(0)
    ->setLeft(0.2)
    ->setHeader(0)
    ->setFooter(0);
*/
$row = 1;
//$sheet->setCellValue("A$row",$company_name);
// Hmm Reset Pointer
$sheet->getStyle("A1");
$sheet->setShowGridlines(false);
$row++;
if ($jnsLaporan == 1) {
    //Laporan Detail
    $sheet->setCellValue("A$row", "LAPORAN TRANSAKSI KAS");
    $row++;
    $sheet->setCellValue("A$row", "Dari Tgl. " . date('d-m-Y', $startDate) . " - " . date('d-m-Y', $endDate));
    $row++;
    $sheet->setCellValue("A$row", "No.");
    $sheet->setCellValue("B$row", "Tanggal");
    $sheet->setCellValue("C$row", "No. Bukti");
    $sheet->setCellValue("D$row", "Keterangan");
    $sheet->setCellValue("E$row", "Masuk");
    $sheet->setCellValue("F$row", "Keluar");
    $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($center, $allBorders));
    $nmr = 0;
    $str = $row;
    if ($reports != null) {
        $debet = 0;
        $kredit = 0;
        $saldo = 0;
        /** @var $reports Kas[] */
        foreach ($reports as $kas) {
            $row++;
            $nmr++;
            $sheet->setCellValue("A$row", $nmr);
            $sheet->getStyle("A$row")->applyFromArray($center);
            $sheet->setCellValue("B$row", date('d-m-Y', strtotime($kas->TrxDate)));
            $sheet->setCellValue("C$row", $kas->TrxNo);
            $sheet->setCellValue("D$row", $kas->Notes);
            if ($kas->TrxType < 3) {
                $sheet->setCellValue("E$row", $kas->Jumlah);
                $debet+= $kas->Jumlah;
            } else {
                $sheet->setCellValue("F$row", $kas->Jumlah);
                $kredit+= $kas->Jumlah;
            }
            $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
        }
        $saldo = $debet - $kredit;
        $edr = $row;
        $row++;
        $sheet->setCellValue("A$row", "Total...");
        $sheet->mergeCells("A$row:D$row");
        $sheet->getStyle("A$row")->applyFromArray($center);
        $sheet->setCellValue("E$row", "=SUM(E$str:E$edr)");
        $sheet->setCellValue("F$row", "=SUM(F$str:F$edr)");
        $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
        $row++;
        $sheet->setCellValue("A$row", "Saldo...");
        $sheet->mergeCells("A$row:D$row");
        $sheet->getStyle("A$row")->applyFromArray($center);
        $sheet->setCellValue("E$row", $saldo);
        $sheet->getStyle("E$str:F$row")->applyFromArray($idrFormat);
        $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
        $row++;
    }
}else {
    //Laporan Rekapitulasi
    $sheet->setCellValue("A$row", "LAPORAN REKAPITULASI KAS");
    $row++;
    $sheet->setCellValue("A$row", "Dari Tgl. " . date('d-m-Y', $startDate) . " - " . date('d-m-Y', $endDate));
    $row++;
    $sheet->setCellValue("A$row", "No.");
    $sheet->setCellValue("B$row", "Tanggal");
    $sheet->setCellValue("C$row", "Masuk");
    $sheet->setCellValue("D$row", "Keluar");
    $sheet->getStyle("A$row:D$row")->applyFromArray(array_merge($center, $allBorders));
    $nmr = 0;
    $str = $row;
    if ($reports != null) {
        $debet = 0;
        $kredit = 0;
        $saldo = 0;
        while ($data = $reports->FetchAssoc()) {
            $row++;
            $nmr++;
            $sheet->setCellValue("A$row", $nmr);
            $sheet->getStyle("A$row")->applyFromArray($center);
            $sheet->setCellValue("B$row", date('d-m-Y', strtotime($data['trx_date'])));
            $sheet->setCellValue("C$row", $data['masuk']);
            $debet += $data['masuk'];
            $sheet->setCellValue("D$row", $data['keluar']);
            $kredit += $data['keluar'];
            $sheet->getStyle("A$row:D$row")->applyFromArray(array_merge($allBorders));
        }
    }
    $saldo = $debet - $kredit;
    $edr = $row;
    $row++;
    $sheet->setCellValue("A$row", "Total...");
    $sheet->mergeCells("A$row:B$row");
    $sheet->getStyle("A$row")->applyFromArray($center);
    $sheet->setCellValue("C$row", "=SUM(C$str:C$edr)");
    $sheet->setCellValue("D$row", "=SUM(D$str:D$edr)");
    $sheet->getStyle("A$row:D$row")->applyFromArray(array_merge($allBorders));
    $row++;
    $sheet->setCellValue("A$row", "Saldo...");
    $sheet->mergeCells("A$row:B$row");
    $sheet->getStyle("A$row")->applyFromArray($center);
    $sheet->setCellValue("C$row", $saldo);
    $sheet->getStyle("C$str:D$row")->applyFromArray($idrFormat);
    $sheet->getStyle("A$row:D$row")->applyFromArray(array_merge($allBorders));
    $row++;
}

// Flush to client
foreach ($headers as $header) {
    header($header);
}
// Hack agar client menutup loading dialog box... (Ada JS yang checking cookie ini pada common.js)
$writer->save("php://output");

// Garbage Collector
$phpExcel->disconnectWorksheets();
unset($phpExcel);
ob_flush();
