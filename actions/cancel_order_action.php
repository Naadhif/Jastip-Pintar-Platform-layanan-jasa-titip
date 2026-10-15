<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$u = currentUser();
$id = $_POST['order_id'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) { flash('error','Pesanan tidak ditemukan.'); redirect(base_url($u['role']==='admin'?'admin/orders.php':'user/my_orders.php')); }
if ($u['role'] === 'user' && ($order['user_id'] != $u['id'] || $order['status'] !== 'Menunggu Konfirmasi')) {
    flash('error','Pesanan tidak dapat dibatalkan.'); redirect(base_url('user/my_orders.php'));
}

$pdo->prepare("UPDATE orders SET status='Dibatalkan' WHERE id=?")->execute([$id]);
$pdo->prepare("INSERT INTO order_history (order_id,status) VALUES (?,?)")->execute([$id,'Dibatalkan']);
flash('success','Pesanan dibatalkan.');
redirect(base_url($u['role']==='admin' ? 'admin/order_detail.php?id='.$id : 'user/my_orders.php'));