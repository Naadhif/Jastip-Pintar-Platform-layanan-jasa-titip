<?php
require_once __DIR__ . '/includes/functions.php';
if (currentUser()) redirect(base_url(currentUser()['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
$f = getFlash();
?>
<!DOCTYPE html>
<html class="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk | JastipPintar</title>
<link rel="icon" type="image/svg+xml" href="<?= favicon_data_uri() ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
<script>(function(){ document.documentElement.className = localStorage.getItem('jp_theme') || 'dark'; })();</script>
</head>
<body>
<div class="theme-toggle-floating"><button class="btn-icon" onclick="toggleTheme()"><?= ic('sun') ?></button></div>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="brand" style="justify-content:center;margin-bottom:22px;"><div class="logo-box"><?= ic('logo') ?></div> JastipPintar</div>
    <div class="auth-tabs">
      <div class="auth-tab active">Masuk</div>
      <a class="auth-tab" href="<?= base_url('register.php') ?>" style="display:flex;align-items:center;justify-content:center;">Daftar</a>
    </div>
    <?php if ($f): ?><div class="alert alert-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div><?php endif; ?>
    <form method="POST" action="<?= base_url('actions/login_action.php') ?>">
      <div class="field"><label>Email</label><input class="input" type="email" name="email" placeholder="nama@email.com" required></div>
      <div class="field"><label>Kata Sandi</label><input class="input" type="password" name="password" placeholder="••••••••" required></div>
      <button class="btn btn-primary btn-block" type="submit"><?= ic('arrowRight') ?> Masuk</button>
    </form>
    <div class="demo-hint"><b>Akun Demo:</b><br>Admin → admin@jastippintar.com / admin123<br>Pengguna → user@jastippintar.com / user123</div>
    <div style="text-align:center;margin-top:18px;"><a href="<?= base_url('index.php') ?>" style="font-size:13px;color:var(--text-dim);">&larr; Kembali ke beranda</a></div>
  </div>
</div>
<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>