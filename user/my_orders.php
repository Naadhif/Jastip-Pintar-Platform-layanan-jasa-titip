<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();
$filter = $_GET['status'] ?? 'Semua';

$sql = "SELECT * FROM orders WHERE user_id=? AND status != 'Selesai'";
$params = [$u['id']];
if ($filter !== 'Semua') { $sql .= " AND status=?"; $params[] = $filter; }
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$orders = $stmt->fetchAll();

// Pesanan dengan status "Selesai" dipindahkan otomatis ke halaman Riwayat Pesanan
$activeFilters = array_merge(['Semua'], array_diff(STATUS_STEPS, ['Selesai']), ['Dibatalkan']);
$pageTitle='Pesanan Saya'; $pageSub='Lacak dan kelola pesanan titip belanja yang sedang berjalan'; $active='myorders';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="flex between" style="margin-bottom:16px; flex-wrap:wrap; gap:10px;">
  <div class="tabs-row" style="margin-bottom:0;">
    <?php foreach ($activeFilters as $f): ?>
    <a class="tab-chip <?=$filter===$f?'active':''?>" href="?status=<?=urlencode($f)?>"><?=$f?></a>
    <?php endforeach; ?>
  </div>
  <a class="btn btn-ghost btn-sm" href="<?=base_url('user/history.php')?>"><?=ic('archive')?> Lihat Riwayat Selesai</a>
</div>
<?php if (empty($orders)): ?>
  <div class="empty-state"><?=ic('truck')?><h4>Tidak ada pesanan aktif</h4><p>Pesanan yang sudah selesai dapat dilihat di halaman Riwayat Pesanan.</p></div>
<?php else: foreach ($orders as $o):
    $cnt = $pdo->prepare("SELECT COUNT(*) c FROM order_items WHERE order_id=?"); $cnt->execute([$o['id']]); $itemCount=$cnt->fetch()['c'];
    $idx = array_search($o['status'], STATUS_STEPS);
?>
<div class="order-card">
  <div class="order-top">
    <div><div class="order-id"><?=e($o['id'])?> <?php if($o['merge_group']):?><span class="badge badge-shop"><?=ic('merge')?> Gabungan</span><?php endif;?></div>
      <div class="order-meta"><?=e($o['store'])?> • <?=e($o['order_date'])?> • <?=e($o['slot'])?><?php if($o['distance_km']):?> • <?=number_format($o['distance_km'],1)?> km<?php endif;?></div></div>
    <span class="badge <?=statusBadgeClass($o['status'])?>"><?=e($o['status'])?></span>
  </div>
  <?php if ($o['status']!=='Dibatalkan' && $idx !== false): ?>
  <div class="stepper">
    <?php foreach (STATUS_STEPS as $i=>$s): ?>
    <div class="step-dot-wrap">
      <div class="step-line <?=$i<=$idx?'done':''?>"></div>
      <div class="step-dot <?=$i<$idx?'done':''?> <?=$i===$idx?'active':''?>"><?= $i<$idx ? ic('check') : $i+1 ?></div>
      <div class="step-label"><?=$s?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <div class="flex between" style="margin-top:10px;">
    <div style="font-size:12.5px;color:var(--text-dim);"><?=$itemCount?> item • Total: <b style="color:var(--text);"><?=rupiah($o['total_fee'])?></b></div>
    <a class="btn btn-ghost btn-sm" href="<?=base_url('user/order_detail.php?id='.$o['id'])?>"><?=ic('eye')?> Detail</a>
  </div>
</div>
<?php endforeach; endif; ?>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>