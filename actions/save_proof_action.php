<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_POST['order_id'] ?? '';
$proof = trim($_POST['proof'] ?? '');
$pdo->prepare("UPDATE orders SET proof=? WHERE id=?")->execute([$proof,$id]);
flash('success','Bukti pembelian disimpan.');
redirect(base_url('admin/order_detail.php?id='.$id));