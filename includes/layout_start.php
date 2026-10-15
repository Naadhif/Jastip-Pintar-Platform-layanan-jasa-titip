<?php
$u = currentUser();
?>
<!DOCTYPE html>
<html class="<?= $_COOKIE['theme'] ?? 'dark' ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> | JastipPintar</title>
<link rel="icon" type="image/svg+xml" href="<?= favicon_data_uri() ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<?php if (!empty($extraHead)) echo $extraHead; ?>
<script>
(function(){ document.documentElement.className = localStorage.getItem('jp_theme') || 'dark'; })();
</script>
</head>
<body>
<div class="shell">
<?php require __DIR__ . '/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div class="flex gap14">
      <button class="btn-icon hamburger" onclick="toggleSidebar()"><?= ic('menu') ?></button>
      <div><div class="page-title"><?= e($pageTitle) ?></div><div class="page-sub"><?= e($pageSub) ?></div></div>
    </div>
    <div class="flex gap10">
      <button class="btn-icon" onclick="toggleTheme()"><?= ic('sun') ?></button>
      <div class="btn-icon"><?= ic('bell') ?></div>
    </div>
  </div>
  <div class="content">
    <?php $f = getFlash(); if ($f): ?>
      <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endif; ?>