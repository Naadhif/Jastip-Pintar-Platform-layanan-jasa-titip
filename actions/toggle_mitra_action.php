<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$id = $_POST['mitra_id'] ?? 0;
$pdo->prepare("UPDATE mitra SET active = NOT active WHERE id = ?")->execute([$id]);
redirect(base_url('admin/mitra.php'));