<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/icons.php';

define('BASE_URL', '/jastippintar/');
define('ADMIN_CODE', 'ADMIN2026');

const CATEGORIES = [
  'konsumsi'    => ['label' => 'Konsumsi', 'icon' => 'cart'],
  'kebersihan'  => ['label' => 'Kebersihan', 'icon' => 'box'],
  'kuliah'      => ['label' => 'Perlengkapan Kuliah', 'icon' => 'receipt'],
];
const ZONES = [
  'dekat'  => ['label' => 'Dekat (<1km)', 'fee' => 0],
  'sedang' => ['label' => 'Sedang (1-3km)', 'fee' => 2000],
  'jauh'   => ['label' => 'Jauh (>3km)', 'fee' => 5000],
];
// ===================== LOKASI & TOKO (REAL MAP DATA) =====================
const CAMPUS_LAT = -5.1614;  // Pusat default peta: sekitar UNM Gunung Sari, Makassar
const CAMPUS_LNG = 119.4257;

const DISTANCE_RATE_PER_KM = 2000; // Rp per km setelah melewati jarak gratis
const FREE_DISTANCE_KM = 1;        // 1 km pertama gratis ongkos antar

const STORE_CATEGORIES = [
  'minimarket' => ['label' => 'Minimarket', 'icon' => 'store'],
  'kantin'     => ['label' => 'Kantin & Warung Makan', 'icon' => 'bowl'],
  'apotek'     => ['label' => 'Apotek & Kesehatan', 'icon' => 'medical'],
  'atk'        => ['label' => 'Alat Tulis & Fotokopi', 'icon' => 'book'],
  'laundry'    => ['label' => 'Laundry', 'icon' => 'drop'],
  'kelontong'  => ['label' => 'Kelontong & Sembako', 'icon' => 'box'],
  'minuman'    => ['label' => 'Minuman & Snack', 'icon' => 'cup'],
];

const STORES = [
  ['name'=>'Indomaret AP Pettarani','category'=>'minimarket','address'=>'Jl. A.P. Pettarani','lat'=>-5.1583,'lng'=>119.4303],
  ['name'=>'Alfamart Tidung','category'=>'minimarket','address'=>'Jl. Tidung Mariolo','lat'=>-5.1565,'lng'=>119.4278],
  ['name'=>'Indomaret Tidung Raya','category'=>'minimarket','address'=>'Jl. Tidung Raya','lat'=>-5.1548,'lng'=>119.4295],
  ['name'=>'Alfamart AP Pettarani','category'=>'minimarket','address'=>'Jl. A.P. Pettarani','lat'=>-5.1570,'lng'=>119.4320],
  ['name'=>'Indomaret Gunung Sari','category'=>'minimarket','address'=>'Jl. Gunung Sari Baru','lat'=>-5.1625,'lng'=>119.4240],
  ['name'=>'Alfamidi Banta-Bantaeng','category'=>'minimarket','address'=>'Jl. Banta-Bantaeng','lat'=>-5.1650,'lng'=>119.4310],
  ['name'=>'Kantin FT UNM','category'=>'kantin','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1616,'lng'=>119.4259],
  ['name'=>'Kantin FMIPA UNM','category'=>'kantin','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1610,'lng'=>119.4265],
  ['name'=>'Kantin Pusat UNM','category'=>'kantin','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1620,'lng'=>119.4250],
  ['name'=>'Kantin FIK UNM','category'=>'kantin','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1605,'lng'=>119.4245],
  ['name'=>'Warung Bu Siti','category'=>'kantin','address'=>'Jl. Mannuruki','lat'=>-5.1640,'lng'=>119.4270],
  ['name'=>'Warteg Barokah','category'=>'kantin','address'=>'Jl. A.P. Pettarani','lat'=>-5.1598,'lng'=>119.4310],
  ['name'=>'Warkop Phoenam Tidung','category'=>'kantin','address'=>'Jl. Tidung Mariolo','lat'=>-5.1560,'lng'=>119.4260],
  ['name'=>'Nasi Kuning Daeng Rala','category'=>'kantin','address'=>'Jl. Abd. Dg. Sirua','lat'=>-5.1680,'lng'=>119.4290],
  ['name'=>'Ayam Geprek Bu Rahma','category'=>'kantin','address'=>'Jl. Hertasning','lat'=>-5.1630,'lng'=>119.4330],
  ['name'=>'Bakso Pak Darmo','category'=>'kantin','address'=>'Jl. Gunung Sari Baru','lat'=>-5.1595,'lng'=>119.4240],
  ['name'=>'Mie Gacoan Pettarani','category'=>'kantin','address'=>'Jl. A.P. Pettarani','lat'=>-5.1540,'lng'=>119.4330],
  ['name'=>'Apotek Kimia Farma Pettarani','category'=>'apotek','address'=>'Jl. A.P. Pettarani','lat'=>-5.1575,'lng'=>119.4315],
  ['name'=>'Apotek Sehat Bersama','category'=>'apotek','address'=>'Jl. Abd. Dg. Sirua','lat'=>-5.1660,'lng'=>119.4260],
  ['name'=>'Apotek Viva Generik Tidung','category'=>'apotek','address'=>'Jl. Tidung Mariolo','lat'=>-5.1555,'lng'=>119.4285],
  ['name'=>'Fotokopi UNM Corner','category'=>'atk','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1612,'lng'=>119.4262],
  ['name'=>'Toko Buku Zanzibar','category'=>'atk','address'=>'Jl. Sultan Alauddin','lat'=>-5.1490,'lng'=>119.4350],
  ['name'=>'ATK Cahaya Ilmu','category'=>'atk','address'=>'Jl. Gunung Sari Baru','lat'=>-5.1635,'lng'=>119.4245],
  ['name'=>'Fotokopi Pak Haji','category'=>'atk','address'=>'Jl. Tidung Mariolo','lat'=>-5.1600,'lng'=>119.4275],
  ['name'=>'Laundry Kilat Tidung','category'=>'laundry','address'=>'Jl. Tidung Raya','lat'=>-5.1545,'lng'=>119.4270],
  ['name'=>'Laundry Mahasiswa Pettarani','category'=>'laundry','address'=>'Jl. A.P. Pettarani','lat'=>-5.1590,'lng'=>119.4325],
  ['name'=>'Toko Kelontong Bu Ani','category'=>'kelontong','address'=>'Jl. Gunung Sari Baru','lat'=>-5.1645,'lng'=>119.4235],
  ['name'=>'Toko Sembako Berkah','category'=>'kelontong','address'=>'Jl. Hertasning','lat'=>-5.1700,'lng'=>119.4300],
  ['name'=>'Pasar Segar Tidung','category'=>'kelontong','address'=>'Jl. Tidung Mariolo','lat'=>-5.1520,'lng'=>119.4260],
  ['name'=>'Thai Tea Corner','category'=>'minuman','address'=>'Jl. Banta-Bantaeng','lat'=>-5.1608,'lng'=>119.4280],
  ['name'=>'Kopi Janji Jiwa Pettarani','category'=>'minuman','address'=>'Jl. A.P. Pettarani','lat'=>-5.1565,'lng'=>119.4335],
  ['name'=>'Chatime Tidung','category'=>'minuman','address'=>'Jl. Tidung Mariolo','lat'=>-5.1535,'lng'=>119.4255],
  ['name'=>'Indomie Corner Kantin','category'=>'minuman','address'=>'Dalam Kampus UNM Gunung Sari','lat'=>-5.1618,'lng'=>119.4258],
];

// ===================== PERHITUNGAN JARAK (HAVERSINE) =====================
function haversine($lat1, $lon1, $lat2, $lon2){
    $R = 6371; // radius bumi (km)
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}

function calcDistanceFee($km){
    if ($km <= FREE_DISTANCE_KM) return 0;
    $chargeable = $km - FREE_DISTANCE_KM;
    $fee = $chargeable * DISTANCE_RATE_PER_KM;
    return (int) (ceil($fee / 500) * 500);
}

function distanceZoneLabel($km){
    if ($km <= 1) return 'Dekat (<1km)';
    if ($km <= 3) return 'Sedang (1-3km)';
    return 'Jauh (>3km)';
}
const SLOTS = ['08:00 - 10:00','10:00 - 12:00','13:00 - 15:00','15:00 - 17:00','17:00 - 19:00'];
const STATUS_STEPS = ['Menunggu Konfirmasi','Dikonfirmasi','Sedang Dibelanjakan','Dalam Perjalanan','Selesai'];
const SERVICE_BASE = 8000;

function rupiah($n){ return 'Rp' . number_format((float)$n, 0, ',', '.'); }
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function base_url($path = ''){ return BASE_URL . ltrim($path, '/'); }
function redirect($url){ header("Location: $url"); exit; }

function flash($type, $msg){ $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; }
function getFlash(){
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

function currentUser(){ return $_SESSION['user'] ?? null; }

function requireLogin($role = null){
    $u = currentUser();
    if (!$u) redirect(base_url('login.php'));
    if ($role && $u['role'] !== $role) {
        redirect(base_url($u['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
    }
}

function generateOrderId(){ return 'JP' . strtoupper(substr(uniqid(), -6)); }

function statusBadgeClass($status){
    $map = [
        'Menunggu Konfirmasi' => 'badge-wait', 'Dikonfirmasi' => 'badge-confirm',
        'Sedang Dibelanjakan' => 'badge-shop', 'Dalam Perjalanan' => 'badge-ship',
        'Selesai' => 'badge-done', 'Dibatalkan' => 'badge-cancel'
    ];
    return $map[$status] ?? 'badge-wait';
}

function timeAgo($datetime){
    $diff = time() - strtotime($datetime);
    $m = floor($diff / 60);
    if ($m < 1) return 'baru saja';
    if ($m < 60) return $m . ' menit lalu';
    $h = floor($m / 60);
    if ($h < 24) return $h . ' jam lalu';
    return floor($h / 24) . ' hari lalu';
}

function initials($name){
    $parts = explode(' ', trim($name));
    $i = '';
    foreach (array_slice($parts, 0, 2) as $p) $i .= strtoupper($p[0] ?? '');
    return $i ?: 'U';
}