<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? AND status='Selesai' ORDER BY created_at DESC");
$stmt->execute([$u['id']]);
$orders = $stmt->fetchAll();

$pageTitle='Riwayat Pesanan'; $pageSub='Daftar pesanan yang telah selesai'; $active='history';
require __DIR__ . '/../includes/layout_start.php';
?>
<?php if (empty($orders)): ?>
  <div class="empty-state"><?=ic('archive')?><h4>Belum ada riwayat</h4><p>Pesanan yang telah selesai akan otomatis muncul di sini.</p></div>
<?php else: foreach ($orders as $o):
    $cnt = $pdo->prepare("SELECT COUNT(*) c FROM order_items WHERE order_id=?"); $cnt->execute([$o['id']]); $itemCount=$cnt->fetch()['c'];
?>
<div class="order-card">
  <div class="order-top">
    <div><div class="order-id"><?=e($o['id'])?></div>
      <div class="order-meta"><?=e($o['store'])?> • <?=e($o['order_date'])?> • <?=e($o['slot'])?><?php if($o['distance_km']):?> • <?=number_format($o['distance_km'],1)?> km<?php endif;?></div></div>
    <span class="badge badge-done"><?=ic('checkCircle')?> Selesai</span>
  </div>
  <div class="flex between" style="margin-top:10px;">
    <div style="font-size:12.5px;color:var(--text-dim);"><?=$itemCount?> item • Total: <b style="color:var(--text);"><?=rupiah($o['total_fee'])?></b></div>
    <a class="btn btn-ghost btn-sm" href="<?=base_url('user/order_detail.php?id='.$o['id'])?>"><?=ic('eye')?> Detail</a>
  </div>
</div>
<?php endforeach; endif; ?>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>