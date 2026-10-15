<?php
$role = $u['role'];
$userLinks = [
  ['dashboard','home','Dashboard','user/dashboard.php'],
  ['order','plus','Buat Pesanan','user/create_order.php'],
  ['myorders','truck','Pesanan Saya','user/my_orders.php'],
  ['history','archive','Riwayat Pesanan','user/history.php'],
  ['schedule','calendar','Jadwal Layanan','user/schedule.php'],
  ['profile','user','Profil','user/profile.php'],
];
$adminLinks = [
  ['dashboard','home','Dashboard','admin/dashboard.php'],
  ['orders','truck','Kelola Pesanan','admin/orders.php'],
  ['merge','merge','Gabungkan Pesanan','admin/merge.php'],
  ['users','users','Pengguna','admin/users.php'],
  ['mitra','store','Mitra Jastip','admin/mitra.php'],
  ['report','chart','Laporan','admin/report.php'],
  ['profile','user','Profil','admin/profile.php'],
];
$links = $role === 'admin' ? $adminLinks : $userLinks;
?>
<div class="sidebar">
  <div class="sidebar-brand">
    <div class="brand" style="font-size:17px;"><div class="logo-box" style="width:32px;height:32px;"><?= ic('logo') ?></div> JastipPintar</div>
  </div>
  <div class="side-nav">
    <?php foreach ($links as [$key,$icon,$label,$path]): ?>
      <a class="side-link <?= ($active ?? '') === $key ? 'active' : '' ?>" href="<?= base_url($path) ?>"><?= ic($icon) ?> <?= $label ?></a>
    <?php endforeach; ?>
  </div>
  <div class="side-foot">
    <div class="user-chip">
      <div class="avatar"><?= initials($u['name']) ?></div>
      <div style="overflow:hidden;">
        <div style="font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($u['name']) ?></div>
        <div style="font-size:11px;color:var(--text-dim);"><?= $role === 'admin' ? 'Administrator' : 'Pengguna' ?></div>
      </div>
    </div>
    <a class="btn btn-ghost btn-block btn-sm" href="<?= base_url('actions/logout_action.php') ?>"><?= ic('logout') ?> Keluar</a>
  </div>
</div>