<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$u = currentUser();
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$pdo->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?")->execute([$name,$phone,$address,$u['id']]);
$_SESSION['user']['name'] = $name;
$_SESSION['user']['phone'] = $phone;
$_SESSION['user']['address'] = $address;

flash('success','Profil berhasil diperbarui.');
redirect(base_url($u['role']==='admin' ? 'admin/profile.php' : 'user/profile.php'));