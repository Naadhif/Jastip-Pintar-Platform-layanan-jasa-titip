<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
$totalRevenue = array_sum(array_map(fn($o)=>$o['status']==='Selesai'?$o['total_fee']:0, $orders));
$totalUsers = $pdo->query("SELECT COUNT(*) c FROM users WHERE role='user'")->fetch()['c'];
$activeCount = count(array_filter($orders, fn($o)=>!in_array($o['status'],['Selesai','Dibatalkan'])));

$counts = [];
foreach (array_merge(STATUS_STEPS, ['Dibatalkan']) as $s) {
    $counts[$s] = count(array_filter($orders, fn($o)=>$o['status']===$s));
}
$maxC = max(1, ...array_values($counts));

$pageTitle='Dashboard Admin'; $pageSub='Ringkasan performa layanan JastipPintar'; $active='dashboard';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="kpi-grid">
  <div class="kpi"><div class="ic-wrap" style="background:var(--primary-dim);color:var(--primary2);"><?=ic('cart')?></div><div class="num"><?=count($orders)?></div><div class="lbl">Total Pesanan</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:#4da3ff22;color:#4da3ff;"><?=ic('truck')?></div><div class="num"><?=$activeCount?></div><div class="lbl">Pesanan Aktif</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:#22c55e22;color:#22c55e;"><?=ic('wallet')?></div><div class="num"><?=rupiah($totalRevenue)?></div><div class="lbl">Pendapatan Jasa</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:#b366ff22;color:#b366ff;"><?=ic('users')?></div><div class="num"><?=$totalUsers?></div><div class="lbl">Pengguna Terdaftar</div></div>
</div>

<div class="grid" style="grid-template-columns:1.3fr 1fr;">
  <div class="card">
    <h3 style="margin-bottom:16px;">Pesanan Terbaru</h3>
    <?php if (empty($orders)): ?>
      <div class="empty-state"><?=ic('cart')?><h4>Belum ada pesanan</h4></div>
    <?php else: foreach (array_slice($orders,0,5) as $o): ?>
    <div class="flex between" style="padding:12px 0;border-bottom:1px solid var(--border);">
      <div><div style="font-weight:700;font-size:13.5px;"><?=e($o['id'])?> — <?=e($pdo->prepare("SELECT name FROM users WHERE id=?") && false ? '' : '')?>
        <?php $un=$pdo->prepare("SELECT name FROM users WHERE id=?"); $un->execute([$o['user_id']]); echo e($un->fetch()['name'] ?? '-'); ?></div>
        <div style="font-size:12px;color:var(--text-dim);"><?=e($o['store'])?> • <?=timeAgo($o['created_at'])?></div></div>
      <span class="badge <?=statusBadgeClass($o['status'])?>"><?=e($o['status'])?></span>
    </div>
    <?php endforeach; endif; ?>
  </div>
  <div class="card">
    <h3 style="margin-bottom:16px;">Distribusi Status Pesanan</h3>
    <div class="bar-wrap">
      <?php foreach ($counts as $k=>$v): ?>
      <div class="bar-row"><div style="width:130px;"><?=$k?></div><div class="bar-track"><div class="bar-fill" style="width:<?=($v/$maxC)*100?>%;"></div></div><b><?=$v?></b></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>