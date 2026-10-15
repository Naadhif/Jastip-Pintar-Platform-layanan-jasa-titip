<?php
require_once __DIR__ . '/includes/functions.php';
if (currentUser()) redirect(base_url(currentUser()['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
$f = getFlash();
$role = $_GET['role'] ?? 'user';
?>
<!DOCTYPE html>
<html class="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar | JastipPintar</title>
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
      <a class="auth-tab" href="<?= base_url('login.php') ?>" style="display:flex;align-items:center;justify-content:center;">Masuk</a>
      <div class="auth-tab active">Daftar</div>
    </div>
    <?php if ($f): ?><div class="alert alert-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div><?php endif; ?>
    <form method="POST" action="<?= base_url('actions/register_action.php') ?>">
      <div class="field">
        <label>Daftar sebagai</label>
        <div class="role-select">
          <label class="role-opt <?= $role==='user'?'active':'' ?>">
            <input type="radio" name="role" value="user" style="display:none;" onclick="location.search='?role=user'" <?= $role==='user'?'checked':'' ?>>
            <?= ic('user') ?><div>Pengguna</div>
          </label>
          <label class="role-opt <?= $role==='admin'?'active':'' ?>">
            <input type="radio" name="role" value="admin" style="display:none;" onclick="location.search='?role=admin'" <?= $role==='admin'?'checked':'' ?>>
            <?= ic('shield') ?><div>Admin</div>
          </label>
        </div>
      </div>
      <input type="hidden" name="role" value="<?= e($role) ?>">
      <div class="field"><label>Nama Lengkap</label><input class="input" name="name" required></div>
      <div class="field"><label>Email</label><input class="input" type="email" name="email" required></div>
      <div class="field"><label>No. HP / WhatsApp</label><input class="input" name="phone" required></div>
      <?php if ($role === 'admin'): ?>
      <div class="field"><label>Kode Admin</label><input class="input" name="admin_code" placeholder="Masukkan kode admin"></div>
      <?php endif; ?>
      <div class="field"><label>Kata Sandi</label><input class="input" type="password" name="password" required></div>
      <div class="field"><label>Konfirmasi Kata Sandi</label><input class="input" type="password" name="password2" required></div>
      <button class="btn btn-primary btn-block" type="submit"><?= ic('check') ?> Buat Akun</button>
    </form>
    <?php if ($role === 'admin'): ?><div class="demo-hint">Kode admin demo: <b>ADMIN2026</b></div><?php endif; ?>
    <div style="text-align:center;margin-top:18px;"><a href="<?= base_url('index.php') ?>" style="font-size:13px;color:var(--text-dim);">&larr; Kembali ke beranda</a></div>
  </div>
</div>
</body>
</html>