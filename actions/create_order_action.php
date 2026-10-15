<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();

$category = $_POST['category'] ?? 'konsumsi';
$store = trim($_POST['store'] ?? '');
$address = trim($_POST['address'] ?? '');
$date = $_POST['order_date'] ?? date('Y-m-d');
$slot = $_POST['slot'] ?? SLOTS[0];
$note = trim($_POST['note'] ?? '');
$items = $_POST['items'] ?? [];

$customerLat = ($_POST['customer_lat'] ?? '') !== '' ? (float)$_POST['customer_lat'] : null;
$customerLng = ($_POST['customer_lng'] ?? '') !== '' ? (float)$_POST['customer_lng'] : null;
$storeLat = ($_POST['store_lat'] ?? '') !== '' ? (float)$_POST['store_lat'] : null;
$storeLng = ($_POST['store_lng'] ?? '') !== '' ? (float)$_POST['store_lng'] : null;
$submittedDistance = isset($_POST['distance_km']) ? (float)$_POST['distance_km'] : 0;

$items = array_filter($items, fn($it) => trim($it['name'] ?? '') !== '');

if (!$store || !$address || empty($items)) {
    flash('error', 'Lengkapi nama toko, alamat, dan minimal satu barang.');
    redirect(base_url('user/create_order.php'));
}

if ($customerLat === null || $customerLng === null) {
    flash('error', 'Silakan tentukan lokasi pengantaran pada peta terlebih dahulu.');
    redirect(base_url('user/create_order.php'));
}

// Hitung jarak: jika toko diketahui koordinatnya, hitung ulang di server (aman dari manipulasi client).
// Jika toko custom (tanpa koordinat), gunakan estimasi dari client atau fallback 2 km.
if ($storeLat !== null && $storeLng !== null) {
    $distanceKm = haversine($customerLat, $customerLng, $storeLat, $storeLng);
} else {
    $distanceKm = $submittedDistance > 0 ? $submittedDistance : 2.0;
}

$zoneFee = calcDistanceFee($distanceKm);
$zoneLabel = distanceZoneLabel($distanceKm);

$service = SERVICE_BASE;
$totalQty = array_sum(array_map(fn($it) => (int)$it['qty'], $items));
$extra = $totalQty > 5 ? ($totalQty - 5) * 500 : 0;
$total = $service + $zoneFee + $extra;

$id = generateOrderId();
$pdo->prepare("INSERT INTO orders (id,user_id,category,store,address,zone,order_date,slot,note,status,service_fee,zone_fee,extra_fee,total_fee,customer_lat,customer_lng,store_lat,store_lng,distance_km)
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
    ->execute([$id,$u['id'],$category,$store,$address,$zoneLabel,$date,$slot,$note,'Menunggu Konfirmasi',$service,$zoneFee,$extra,$total,$customerLat,$customerLng,$storeLat,$storeLng,$distanceKm]);

$itemStmt = $pdo->prepare("INSERT INTO order_items (order_id,name,qty,note) VALUES (?,?,?,?)");
foreach ($items as $it) {
    $itemStmt->execute([$id, trim($it['name']), max(1,(int)$it['qty']), trim($it['note'] ?? '')]);
}

$pdo->prepare("INSERT INTO order_history (order_id,status) VALUES (?,?)")->execute([$id,'Menunggu Konfirmasi']);

flash('success', "Pesanan berhasil dibuat: $id (jarak " . number_format($distanceKm,2) . " km)");
redirect(base_url('user/my_orders.php'));