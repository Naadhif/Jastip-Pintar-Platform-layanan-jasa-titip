<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$u['id']]);
$allOrders = $stmt->fetchAll();
$orders = array_values(array_filter($allOrders, fn($o) => $o['status'] !== 'Selesai'));

$total = count($allOrders);
$activeCount = count(array_filter($allOrders, fn($o)=>!in_array($o['status'],['Selesai','Dibatalkan'])));
$done = count(array_filter($allOrders, fn($o)=>$o['status']==='Selesai'));
$spend = array_sum(array_map(fn($o)=>$o['status']==='Selesai'?$o['total_fee']:0, $allOrders));

$total = count($orders);
$activeCount = count(array_filter($orders, fn($o)=>!in_array($o['status'],['Selesai','Dibatalkan'])));
$done = count(array_filter($orders, fn($o)=>$o['status']==='Selesai'));
$spend = array_sum(array_map(fn($o)=>$o['status']==='Selesai'?$o['total_fee']:0, $orders));

$pageTitle='Dashboard'; $pageSub='Selamat datang kembali, '.explode(' ',$u['name'])[0].'!'; $active='dashboard';
require __DIR__ . '/../includes/layout_start.php';
?>

<!-- ===== HERO WELCOME BANNER ===== -->
<div class="db-hero">
  <div class="db-hero-blob"></div>
  <div class="db-hero-blob-2"></div>
  <div class="db-hero-content">
    <div class="db-hero-badge"><?=ic('sparkle')?> Platform Titip Belanja Digital</div>
    <h2>Mau titip belanja apa hari ini, <?=e(explode(' ',$u['name'])[0])?>?</h2>
    <p>Pilih toko dari 32+ pilihan sekitar kampus, tentukan jadwal, dan biarkan mitra kami yang membantumu.</p>
    <div class="flex gap10" style="margin-top:20px; flex-wrap:wrap;">
      <a class="btn" style="background:#fff;color:var(--primary);font-weight:700;" href="<?=base_url('user/create_order.php')?>"><?=ic('plus')?> Buat Pesanan Baru</a>
      <a class="btn btn-outline" style="border-color:rgba(255,255,255,.5);color:#fff;" href="<?=base_url('user/my_orders.php')?>">Lihat Pesanan <?=ic('chevRight')?></a>
    </div>
  </div>
  <div class="db-hero-visual">
    <div class="db-hero-card">
      <div class="flex between" style="margin-bottom:10px;">
        <div class="store-ic-sm"><?=ic('truck')?></div>
        <span class="badge" style="background:rgba(255,255,255,.25);color:#fff;">Live</span>
      </div>
      <div style="font-size:11.5px; opacity:.85;">Status pesanan aktif</div>
      <div style="font-size:22px; font-weight:800;"><?=$activeCount?> Pesanan</div>
    </div>
  </div>
</div>

<!-- ===== STAT GRID ===== -->
<div class="kpi-grid">
  <div class="kpi db-kpi"><div class="ic-wrap" style="background:var(--primary-dim); color:var(--primary2);"><?=ic('cart')?></div><div class="num"><?=$total?></div><div class="lbl">Total Pesanan</div></div>
  <div class="kpi db-kpi"><div class="ic-wrap" style="background:#4da3ff22; color:#4da3ff;"><?=ic('truck')?></div><div class="num"><?=$activeCount?></div><div class="lbl">Pesanan Aktif</div></div>
  <a href="<?=base_url('user/history.php')?>" class="kpi db-kpi" style="text-decoration:none;"><div class="ic-wrap" style="background:#22c55e22; color:#22c55e;"><?=ic('checkCircle')?></div><div class="num"><?=$done?></div><div class="lbl">Pesanan Selesai (Riwayat)</div></a>
  <div class="kpi db-kpi"><div class="ic-wrap" style="background:#f5a62322; color:#f5a623;"><?=ic('wallet')?></div><div class="num"><?=rupiah($spend)?></div><div class="lbl">Total Biaya Jasa</div></div>
</div>

<!-- ===== CATEGORY QUICK ACCESS ===== -->
<div class="flex between" style="margin-bottom:16px;">
  <h3>Kategori Toko</h3>
  <a href="<?=base_url('user/create_order.php')?>" style="font-size:13px;color:var(--primary);">Lihat semua toko <?=ic('chevRight')?></a>
</div>
<div class="cat-shortcut-grid">
  <?php foreach (STORE_CATEGORIES as $key=>$cat):
    $count = count(array_filter(STORES, fn($s)=>$s['category']===$key));
  ?>
  <a class="cat-shortcut" href="<?=base_url('user/create_order.php?cat='.$key)?>">
    <div class="cat-shortcut-icon"><?=ic($cat['icon'])?></div>
    <div class="cat-shortcut-label"><?=e($cat['label'])?></div>
    <div class="cat-shortcut-count"><?=$count?> toko</div>
  </a>
  <?php endforeach; ?>
</div>

<!-- ===== RECENT ORDERS + TIPS ===== -->
<div class="grid" style="grid-template-columns:2fr 1fr;">
  <div class="card">
    <div class="flex between" style="margin-bottom:14px;"><h3>Pesanan Terbaru</h3><a href="<?=base_url('user/my_orders.php')?>" style="font-size:13px;color:var(--primary);">Lihat semua <?=ic('chevRight')?></a></div>
    <?php if (empty($orders)): ?>
      <div class="empty-state"><?=ic('cart')?><h4>Belum ada pesanan</h4><p>Mulai buat pesanan titip belanja pertamamu.</p></div>
    <?php else: foreach (array_slice($orders,0,3) as $o):
        $cnt = $pdo->prepare("SELECT COUNT(*) c FROM order_items WHERE order_id=?"); $cnt->execute([$o['id']]); $itemCount=$cnt->fetch()['c'];
        $idx = array_search($o['status'], STATUS_STEPS);
    ?>
    <div class="order-card">
      <div class="order-top">
        <div><div class="order-id"><?=e($o['id'])?> <?php if($o['merge_group']):?><span class="badge badge-shop"><?=ic('merge')?> Gabungan</span><?php endif;?></div>
          <div class="order-meta"><?=e($o['store'])?> • <?=e($o['order_date'])?> • <?=e($o['slot'])?></div></div>
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
  </div>

  <div class="card">
    <h3 style="margin-bottom:14px;"><?=ic('bolt')?> Tips Hemat Waktu</h3>
    <div class="tip-item">
      <div class="tip-ic"><?=ic('calendar')?></div>
      <div><b>Pesan di pagi hari</b><p>Slot 08:00–10:00 biasanya diproses lebih cepat oleh mitra.</p></div>
    </div>
    <div class="tip-item">
      <div class="tip-ic"><?=ic('merge')?></div>
      <div><b>Pesan bareng teman kos</b><p>Toko & jadwal yang sama berpotensi digabung otomatis oleh admin.</p></div>
    </div>
    <div class="tip-item">
      <div class="tip-ic"><?=ic('receipt')?></div>
      <div><b>Cek rincian biaya</b><p>Semua biaya ditampilkan transparan sebelum kamu konfirmasi pesanan.</p></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>