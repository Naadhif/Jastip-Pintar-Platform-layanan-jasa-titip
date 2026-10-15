<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_POST['mitra_id'] ?? 0;
$pdo->prepare("DELETE FROM mitra WHERE id = ?")->execute([$id]);
flash('success','Mitra dihapus.');
redirect(base_url('admin/mitra.php'));