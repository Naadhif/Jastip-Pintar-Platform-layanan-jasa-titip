<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();
$id = $_GET['id'] ?? '';

$stmt = $pdo->prepare("SELECT o.*, m.name AS mitra_name FROM orders o LEFT JOIN mitra m ON o.mitra_id=m.id WHERE o.id=? AND o.user_id=?");
$stmt->execute([$id, $u['id']]);
$order = $stmt->fetch();
if (!$order) { flash('error','Pesanan tidak ditemukan.'); redirect(base_url('user/my_orders.php')); }

$items = $pdo->prepare("SELECT * FROM order_items WHERE order_id=?"); $items->execute([$id]); $items=$items->fetchAll();
$isAdmin = false;

$pageTitle='Detail Pesanan'; $pageSub=$order['id']; $active='myorders';
require __DIR__ . '/../includes/layout_start.php';
require __DIR__ . '/../includes/order_detail_view.php';
require __DIR__ . '/../includes/layout_end.php';