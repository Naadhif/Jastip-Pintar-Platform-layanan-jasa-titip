<?php
require_once __DIR__ . '/../includes/functions.php';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$role = $_POST['role'] === 'admin' ? 'admin' : 'user';

if (!$name || !$email || !$phone || !$password) { flash('error','Lengkapi semua data.'); redirect(base_url('register.php?role='.$role)); }
if (strlen($password) < 6) { flash('error','Kata sandi minimal 6 karakter.'); redirect(base_url('register.php?role='.$role)); }
if ($password !== $password2) { flash('error','Konfirmasi kata sandi tidak cocok.'); redirect(base_url('register.php?role='.$role)); }

if ($role === 'admin' && ($_POST['admin_code'] ?? '') !== ADMIN_CODE) {
    flash('error','Kode admin tidak valid.'); redirect(base_url('register.php?role=admin'));
}

$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);
if ($check->fetch()) { flash('error','Email sudah terdaftar, silakan masuk.'); redirect(base_url('register.php?role='.$role)); }

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->prepare("INSERT INTO users (name,email,password,phone,address,role) VALUES (?,?,?,?,?,?)")
    ->execute([$name,$email,$hash,$phone,'',$role]);

$id = $pdo->lastInsertId();
$_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email,'role'=>$role,'phone'=>$phone,'address'=>''];
flash('success', 'Akun berhasil dibuat. Selamat bergabung!');
redirect(base_url($role === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));