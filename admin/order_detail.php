<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_GET['id'] ?? '';

$stmt = $pdo->prepare("SELECT o.*, m.name AS mitra_name, u.name AS user_name FROM orders o LEFT JOIN mitra m ON o.mitra_id=m.id LEFT JOIN users u ON o.user_id=u.id WHERE o.id=?");
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) { flash('error','Pesanan tidak ditemukan.'); redirect(base_url('admin/orders.php')); }

$items = $pdo->prepare("SELECT * FROM order_items WHERE order_id=?"); $items->execute([$id]); $items=$items->fetchAll();
$mitraList = $pdo->query("SELECT * FROM mitra WHERE active=1")->fetchAll();
$isAdmin = true;

$pageTitle='Detail Pesanan'; $pageSub=$order['id']; $active='orders';
require __DIR__ . '/../includes/layout_start.php';
require __DIR__ . '/../includes/order_detail_view.php';
require __DIR__ . '/../includes/layout_end.php';