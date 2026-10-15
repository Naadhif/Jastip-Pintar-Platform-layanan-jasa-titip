<?php
require_once __DIR__ . '/../includes/functions.php';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    flash('error', 'Email atau kata sandi salah.');
    redirect(base_url('login.php'));
}

$_SESSION['user'] = [
    'id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'],
    'role' => $user['role'], 'phone' => $user['phone'], 'address' => $user['address'],
];
flash('success', 'Selamat datang, ' . $user['name'] . '!');
redirect(base_url($user['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));