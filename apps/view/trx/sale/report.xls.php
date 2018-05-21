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
, 'Content-Disposition: attachment;filename="print-sales-transaction.xls"'
, 'Cache-Control: max-age=0'
);
$writer = new PHPExcel_Writer_Excel5($phpExcel);
// Excel MetaData
$phpExcel->getProperties()->setCreator("Erasystem Infotama Inc (c) Budi Aditya")->setTitle("Print Laporan")->setCompany("Erasystem Infotama Inc");
$sheet = $phpExcel->getActiveSheet();
$sheet->setTitle("Rekapitulasi Penjualan");
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
    $sheet->setCellValue("A$row", "LAPORAN PENJUALAN");
    $row++;
    $sheet->setCellValue("A$row", "Dari Tgl. " . date('d-m-Y', $startDate) . " - " . date('d-m-Y', $endDate));
    $row++;
    $sheet->setCellValue("A$row", "No.");
    $sheet->setCellValue("B$row", "Tanggal");
    $sheet->setCellValue("C$row", "No. Transaksi");
    $sheet->setCellValue("D$row", "Customer");
    $sheet->setCellValue("E$row", " + Sub Total");
    $sheet->setCellValue("F$row", " - Diskon");
    $sheet->setCellValue("G$row", " + Pajak");
    $sheet->setCellValue("H$row", " = Jumlah");
    $sheet->getStyle("A$row:H$row")->applyFromArray(array_merge($center, $allBorders));
    $nmr = 0;
    $str = $row;
    if ($reports != null) {
        /** @var $reports Sale[] */
        foreach ($reports as $sale) {
            $row++;
            $nmr++;
            $sheet->setCellValue("A$row", $nmr);
            $sheet->getStyle("A$row")->applyFromArray($center);
            $sheet->setCellValue("B$row", $sale->TrxTime);
            $sheet->setCellValue("C$row", $sale->TrxNo);
            $sheet->setCellValue("D$row", $sale->CustName);
            $sheet->setCellValue("E$row", $sale->SubTotal);
            $sheet->setCellValue("F$row", $sale->DiscAmt);
            $sheet->setCellValue("G$row", $sale->TaxAmt);
            $sheet->setCellValue("H$row", $sale->PayAmt);
            $sheet->getStyle("A$row:H$row")->applyFromArray(array_merge($allBorders));
        }
        $edr = $row;
        $row++;
        $sheet->setCellValue("A$row", "Total...");
        $sheet->mergeCells("A$row:D$row");
        $sheet->getStyle("A$row")->applyFromArray($center);
        $sheet->setCellValue("E$row", "=SUM(E$str:E$edr)");
        $sheet->setCellValue("F$row", "=SUM(F$str:F$edr)");
        $sheet->setCellValue("G$row", "=SUM(G$str:G$edr)");
        $sheet->setCellValue("H$row", "=SUM(H$str:H$edr)");
        $sheet->getStyle("E$str:H$row")->applyFromArray($idrFormat);
        $sheet->getStyle("A$row:H$row")->applyFromArray(array_merge($allBorders));
    }
}elseif ($jnsLaporan == 2) {
    //Laporan Rekapitulasi
    $sheet->setCellValue("A$row", "LAPORAN REKAPITULASI PENJUALAN");
    $row++;
    $sheet->setCellValue("A$row", "Dari Tgl. " . date('d-m-Y', $startDate) . " - " . date('d-m-Y', $endDate));
    $row++;
    $sheet->setCellValue("A$row", "No.");
    $sheet->setCellValue("B$row", "Tanggal");
    $sheet->setCellValue("C$row", " + Sub Total");
    $sheet->setCellValue("D$row", " - Diskon");
    $sheet->setCellValue("E$row", " + Pajak");
    $sheet->setCellValue("F$row", " = Jumlah");
    $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($center, $allBorders));
    $nmr = 0;
    $str = $row;
    if ($reports != null) {
        while ($data = $reports->FetchAssoc()) {
            $row++;
            $nmr++;
            $sheet->setCellValue("A$row", $nmr);
            $sheet->getStyle("A$row")->applyFromArray($center);
            $sheet->setCellValue("B$row", date('d-m-Y', strtotime($data['trx_date'])));
            $sheet->setCellValue("C$row", $data['sub_total']);
            $sheet->setCellValue("D$row", $data['diskon']);
            $sheet->setCellValue("E$row", $data['pajak']);
            $sheet->setCellValue("F$row", $data['jumlah']);
            $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
        }
    }
    $edr = $row;
    $row++;
    $sheet->setCellValue("A$row", "Total...");
    $sheet->mergeCells("A$row:B$row");
    $sheet->getStyle("A$row")->applyFromArray($center);
    $sheet->setCellValue("C$row", "=SUM(C$str:C$edr)");
    $sheet->setCellValue("D$row", "=SUM(D$str:D$edr)");
    $sheet->setCellValue("E$row", "=SUM(E$str:E$edr)");
    $sheet->setCellValue("F$row", "=SUM(F$str:F$edr)");
    $sheet->getStyle("C$str:F$row")->applyFromArray($idrFormat);
    $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
}else{
    //Laporan Rekapitulasi
    $sheet->setCellValue("A$row", "LAPORAN REKAPITULASI PRODUK TERJUAL");
    $row++;
    $sheet->setCellValue("A$row", "Dari Tgl. " . date('d-m-Y', $startDate) . " - " . date('d-m-Y', $endDate));
    $row++;
    $sheet->setCellValue("A$row", "No.");
    $sheet->setCellValue("B$row", "SKU");
    $sheet->setCellValue("C$row", "Nama Produk");
    $sheet->setCellValue("D$row", "QTY");
    $sheet->setCellValue("E$row", "Satuan");
    $sheet->setCellValue("F$row", "Nilai Penjualan");
    $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($center, $allBorders));
    $nmr = 0;
    $str = $row;
    if ($reports != null) {
        while ($data = $reports->FetchAssoc()) {
            $row++;
            $nmr++;
            $sheet->setCellValue("A$row", $nmr);
            $sheet->getStyle("A$row")->applyFromArray($center);
            $sheet->setCellValue("B$row", $data['sku']);
            $sheet->setCellValue("C$row", $data['nama']);
            $sheet->setCellValue("D$row", $data['sum_qty']);
            $sheet->setCellValue("E$row", $data['satuan']);
            $sheet->setCellValue("F$row", $data['sum_jumlah']);
            $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
        }
    }
    $edr = $row;
    $row++;
    $sheet->setCellValue("A$row", "Total (* Sebelum Diskon & Pajak)");
    $sheet->mergeCells("A$row:E$row");
    $sheet->getStyle("A$row")->applyFromArray($center);
    $sheet->setCellValue("F$row", "=SUM(F$str:F$edr)");
    $sheet->getStyle("F$str:F$row")->applyFromArray($idrFormat);
    $sheet->getStyle("A$row:F$row")->applyFromArray(array_merge($allBorders));
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
