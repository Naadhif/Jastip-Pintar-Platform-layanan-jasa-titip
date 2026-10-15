<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();
$pageTitle='Profil'; $pageSub='Kelola informasi akun kamu'; $active='profile';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="card" style="max-width:520px;">
  <div class="flex gap14" style="margin-bottom:22px;">
    <div class="avatar" style="width:56px;height:56px;font-size:20px;"><?=initials($u['name'])?></div>
    <div><h3><?=e($u['name'])?></h3><div style="font-size:13px;color:var(--text-dim);">Pengguna • <?=e($u['email'])?></div></div>
  </div>
  <form method="POST" action="<?=base_url('actions/update_profile_action.php')?>">
    <div class="field"><label>Nama Lengkap</label><input class="input" name="name" value="<?=e($u['name'])?>" required></div>
    <div class="field"><label>Email</label><input class="input" value="<?=e($u['email'])?>" disabled style="opacity:.6;"></div>
    <div class="field"><label>No. HP</label><input class="input" name="phone" value="<?=e($u['phone'])?>" required></div>
    <div class="field"><label>Alamat</label><input class="input" name="address" value="<?=e($u['address'])?>"></div>
    <button class="btn btn-primary btn-block" type="submit"><?=ic('check')?> Simpan Perubahan</button>
  </form>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>