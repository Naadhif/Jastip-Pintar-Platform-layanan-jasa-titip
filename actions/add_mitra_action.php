<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
if ($name && $phone) {
    $pdo->prepare("INSERT INTO mitra (name,phone,active) VALUES (?,?,1)")->execute([$name,$phone]);
    flash('success','Mitra baru ditambahkan.');
} else {
    flash('error','Lengkapi nama dan nomor HP mitra.');
}
redirect(base_url('admin/mitra.php'));