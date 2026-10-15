<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_POST['order_id'] ?? '';
$mitraId = $_POST['mitra_id'] ?: null;
$pdo->prepare("UPDATE orders SET mitra_id=? WHERE id=?")->execute([$mitraId,$id]);
flash('success','Mitra berhasil ditetapkan.');
redirect($_SERVER['HTTP_REFERER'] ?? base_url('admin/orders.php'));