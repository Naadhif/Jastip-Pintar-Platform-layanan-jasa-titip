<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_POST['order_id'] ?? '';
$status = $_POST['status'] ?? '';
if (in_array($status, array_merge(STATUS_STEPS, ['Dibatalkan']))) {
    $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status,$id]);
    $pdo->prepare("INSERT INTO order_history (order_id,status) VALUES (?,?)")->execute([$id,$status]);
    flash('success','Status pesanan diperbarui.');
}
redirect($_SERVER['HTTP_REFERER'] ?? base_url('admin/orders.php'));