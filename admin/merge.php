<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');

$stmt = $pdo->prepare("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id=u.id WHERE o.status IN ('Menunggu Konfirmasi','Dikonfirmasi') ORDER BY o.store, o.order_date, o.slot");
$stmt->execute();
$candidates = $stmt->fetchAll();

$groups = [];
foreach ($candidates as $o) {
    $key = $o['store'] . '|' . $o['order_date'] . '|' . $o['slot'];
    $groups[$key][] = $o;
}

$pageTitle='Gabungkan Pesanan'; $pageSub='Satukan pesanan dengan toko, tanggal, dan jadwal yang sama'; $active='merge';
require __DIR__ . '/../includes/layout_start.php';
?>
<?php if (empty($groups)): ?>
  <div class="empty-state"><?=ic('merge')?><h4>Belum ada kandidat</h4><p>Belum ada pesanan yang bisa digabungkan saat ini.</p></div>
<?php else: ?>
<form method="POST" action="<?=base_url('actions/merge_orders_action.php')?>">
  <?php foreach ($groups as $key => $list):
      [$store,$date,$slot] = explode('|', $key);
      $itemsStmt = $pdo->prepare("SELECT name FROM order_items WHERE order_id=?");
  ?>
  <div class="card" style="margin-bottom:18px;">
    <div class="flex between" style="margin-bottom:12px;">
      <div><b><?=ic('store')?> <?=e($store)?></b><div style="font-size:12px;color:var(--text-dim);"><?=e($date)?> • <?=e($slot)?></div></div>
      <?php if (count($list)>1): ?><span class="badge badge-shop"><?=count($list)?> pesanan searah</span><?php else: ?><span class="badge badge-wait">Belum ada pasangan</span><?php endif; ?>
    </div>
    <?php foreach ($list as $o):
        $itemsStmt->execute([$o['id']]);
        $names = implode(', ', array_column($itemsStmt->fetchAll(), 'name'));
    ?>
    <label class="flex gap10" style="padding:10px;border:1px solid var(--border);border-radius:10px;margin-bottom:8px;cursor:pointer;">
      <input type="checkbox" name="order_ids[]" value="<?=e($o['id'])?>" <?=$o['merge_group']?'disabled':''?>>
      <div style="flex:1;">
        <div style="font-size:13px;font-weight:600;"><?=e($o['id'])?> — <?=e($o['user_name'])?> <?php if($o['merge_group']):?><span class="badge badge-shop">Sudah digabung (<?=e($o['merge_group'])?>)</span><?php endif;?></div>
        <div style="font-size:11.5px;color:var(--text-dim);"><?=e($names)?></div>
      </div>
      <div style="font-weight:700;font-size:13px;"><?=rupiah($o['total_fee'])?></div>
    </label>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
  <button class="btn btn-primary" type="submit"><?=ic('merge')?> Gabungkan Pesanan Terpilih</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>