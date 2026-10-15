<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$filter = $_GET['status'] ?? 'Semua';

$sql = "SELECT o.*, m.name AS mitra_name, u.name AS user_name FROM orders o LEFT JOIN mitra m ON o.mitra_id=m.id LEFT JOIN users u ON o.user_id=u.id";
$params = [];
if ($filter !== 'Semua') { $sql .= " WHERE o.status=?"; $params[]=$filter; }
$sql .= " ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $orders = $stmt->fetchAll();

$mitraList = $pdo->query("SELECT * FROM mitra WHERE active=1")->fetchAll();
$filters = array_merge(['Semua'], STATUS_STEPS, ['Dibatalkan']);

$pageTitle='Kelola Pesanan'; $pageSub='Perbarui status, tetapkan mitra, dan kelola seluruh pesanan'; $active='orders';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="tabs-row">
  <?php foreach ($filters as $f): ?>
  <a class="tab-chip <?=$filter===$f?'active':''?>" href="?status=<?=urlencode($f)?>"><?=$f?></a>
  <?php endforeach; ?>
</div>
<div class="card">
  <div class="table-wrap">
  <table>
    <thead><tr><th>ID</th><th>Pelanggan</th><th>Toko</th><th>Jadwal</th><th>Total</th><th>Mitra</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
    <tr>
      <td><b><?=e($o['id'])?></b><?php if($o['merge_group']):?><div class="badge badge-shop" style="margin-top:4px;"><?=ic('merge')?> <?=e($o['merge_group'])?></div><?php endif;?></td>
      <td><?=e($o['user_name'])?></td>
      <td><?=e($o['store'])?></td>
      <td><?=e($o['order_date'])?><br><span style="color:var(--text-dim);font-size:11.5px;"><?=e($o['slot'])?></span></td>
      <td><?=rupiah($o['total_fee'])?></td>
      <td>
        <form method="POST" action="<?=base_url('actions/assign_mitra_action.php')?>">
          <input type="hidden" name="order_id" value="<?=e($o['id'])?>">
          <select class="input" style="padding:6px 8px;font-size:12px;" name="mitra_id" onchange="this.form.submit()">
            <option value="">Pilih mitra</option>
            <?php foreach ($mitraList as $m): ?>
            <option value="<?=$m['id']?>" <?=$o['mitra_id']==$m['id']?'selected':''?>><?=e($m['name'])?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </td>
      <td>
        <form method="POST" action="<?=base_url('actions/update_order_status_action.php')?>">
          <input type="hidden" name="order_id" value="<?=e($o['id'])?>">
          <select class="input" style="padding:6px 8px;font-size:12px;" name="status" onchange="this.form.submit()">
            <?php foreach (array_merge(STATUS_STEPS,['Dibatalkan']) as $s): ?>
            <option value="<?=$s?>" <?=$o['status']===$s?'selected':''?>><?=$s?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </td>
      <td><a class="btn btn-ghost btn-sm" href="<?=base_url('admin/order_detail.php?id='.$o['id'])?>"><?=ic('eye')?></a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php if (empty($orders)): ?><div class="empty-state"><?=ic('truck')?><h4>Tidak ada data</h4></div><?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>