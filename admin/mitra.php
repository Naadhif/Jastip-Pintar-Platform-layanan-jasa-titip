<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');
$mitraList = $pdo->query("SELECT * FROM mitra ORDER BY created_at DESC")->fetchAll();

$pageTitle='Mitra Jastip'; $pageSub='Kelola mitra pelaksana jasa titip belanja'; $active='mitra';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="grid" style="grid-template-columns:1fr 1.4fr;">
  <div class="card">
    <h3 style="margin-bottom:14px;"><?=ic('plus')?> Tambah Mitra</h3>
    <form method="POST" action="<?=base_url('actions/add_mitra_action.php')?>">
      <div class="field"><label>Nama Mitra</label><input class="input" name="name" required></div>
      <div class="field"><label>No. HP</label><input class="input" name="phone" required></div>
      <button class="btn btn-primary btn-block" type="submit">Tambah Mitra</button>
    </form>
  </div>
  <div class="card">
    <h3 style="margin-bottom:14px;">Daftar Mitra (<?=count($mitraList)?>)</h3>
    <?php foreach ($mitraList as $m): ?>
    <div class="flex between" style="padding:12px 0;border-bottom:1px solid var(--border);">
      <div class="flex gap10"><div class="avatar" style="width:34px;height:34px;font-size:12px;"><?=initials($m['name'])?></div>
        <div><div style="font-weight:600;font-size:13.5px;"><?=e($m['name'])?></div><div style="font-size:11.5px;color:var(--text-dim);"><?=e($m['phone'])?></div></div>
      </div>
      <div class="flex gap8">
        <form method="POST" action="<?=base_url('actions/toggle_mitra_action.php')?>">
          <input type="hidden" name="mitra_id" value="<?=$m['id']?>">
          <button class="badge <?=$m['active']?'badge-done':'badge-cancel'?>" style="border:none;cursor:pointer;" type="submit"><?=$m['active']?'Aktif':'Nonaktif'?></button>
        </form>
        <form method="POST" action="<?=base_url('actions/delete_mitra_action.php')?>" onsubmit="return confirm('Hapus mitra ini?')">
          <input type="hidden" name="mitra_id" value="<?=$m['id']?>">
          <button class="btn-icon" type="submit"><?=ic('trash')?></button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>