<?php
/**
 * Later this file will be automatically auto-generated...
 * Menu are stored in database but we create this file for faster menu creation
 */

// Load required library
require_once(LIBRARY . "node.php");

// This act as menu container
$root = new Node("[ROOT]");
$root->AddNode("HOME", "main");
$menu = $root->AddNode("MASTER DATA", null, "menu");
    $menu->AddNode("Fasilitas Medis", null, "title");
        $menu->AddNode("Data Poliklinik", "master.units");
        $menu->AddNode("Data Kamar Rawat", "master.kamar");
        $menu->AddNode("Data Laboratorium", "master.lab");
    $menu->AddNode("Dokter & Petugas", null, "title");
        $menu->AddNode("Data Dokter", "master.dokter");
        $menu->AddNode("Data Karyawan", "master.karyawan");
    $menu->AddNode("Jasa/Tarif & Biaya", null, "title");
        $menu->AddNode("Kelompok Jasa/Tindakan", "master.klpjasa");
        $menu->AddNode("Daftar Jasa/Tindakan", "master.jasa");
        $menu->AddNode("Kelompok Biaya", "master.klpbiaya");
        $menu->AddNode("Daftar Jenis Biaya", "master.biaya");
    $menu->AddNode("Data Coding Penyakit", null, "title");
        $menu->AddNode("Kelompok Penyakit", "master.klppenyakit");
        $menu->AddNode("Daftar Jenis Penyakit", "master.penyakit");
$menu = $root->AddNode("REKAM MEDIS", null, "menu");
    $menu->AddNode("Data Pasien", null, "title");
        $menu->AddNode("Data Pasien", "master.pasien");
    $menu->AddNode("Data Kunjungan", null, "title");
        $menu->AddNode("Data Kunjungan", "inventory.registrasi");
$menu = $root->AddNode("I G D", null, "menu");
    $menu->AddNode("Fasilitas Medis", null, "title");
        $menu->AddNode("Data Poliklinik", "master.units");
        $menu->AddNode("Data Kamar Rawat", "master.kamar");
        $menu->AddNode("Data Laboratorium", "master.lab");
$menu = $root->AddNode("RAWAT JALAN", null, "menu");
    $menu->AddNode("Fasilitas Medis", null, "title");
        $menu->AddNode("Data Poliklinik", "master.units");
        $menu->AddNode("Data Kamar Rawat", "master.kamar");
        $menu->AddNode("Data Laboratorium", "master.lab");
$menu = $root->AddNode("RAWAT INAP", null, "menu");
    $menu->AddNode("Fasilitas Medis", null, "title");
        $menu->AddNode("Data Poliklinik", "master.units");
        $menu->AddNode("Data Kamar Rawat", "master.kamar");
        $menu->AddNode("Data Laboratorium", "master.lab");
$menu = $root->AddNode("INVENTORY", null, "menu");
    $menu->AddNode("Master Data", null, "title");
        $menu->AddNode("Data Bahan Habis Pakai", "master.bhp");
        $menu->AddNode("Data Relasi & Suplier", "master.relasi");
    $menu->AddNode("Transaksi", null, "title");
        $menu->AddNode("Pembelian Bahan", "ivt.pembelian");
        $menu->AddNode("Pembayaran Hutang", "ivt.payment");
    $menu->AddNode("Laporan", null, "title");
        $menu->AddNode("Laporan Bahan", "ivt.report");
$menu = $root->AddNode("PENGATURAN", null, "menu");
    $menu->AddNode("Data Umum", null, "title");
        $menu->AddNode("Data Klinik", "master.company");
        $menu->AddNode("Data Bagian", "master.department");
        $menu->AddNode("Data Karyawan", "master.karyawan");
    $menu->AddNode("Pemakai System", null, "title");
        $menu->AddNode("Pemakai & Hak Akses", "master.useradmin");
    $menu->AddNode("Pengaturan System", null, "title");
        $menu->AddNode("Setting Pengumuman", "master.attention");
        $menu->AddNode("Ganti Periode Transaksi", "main/set_periode");
        $menu->AddNode("Ganti Password Sendiri", "main/change_password");
        $menu->AddNode("Daftar Hak Akses", "main/aclview");
// Special access for corporate
$persistence = PersistenceManager::GetInstance();
$isCorporate = $persistence->LoadState("is_corporate");
$forcePeriode = $persistence->LoadState("force_periode");
/*
if ($forcePeriode) {
	$root->AddNode("Ganti Periode", "main/set_periode");
}
$root->AddNode("Ganti Password", "main/change_password");
*/
//$root->AddNode("Notifikasi", "main");
$root->AddNode("LOGOUT", "home/logout");

// End of file: sitemap.php.php
