<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$pageTitle='Jadwal Layanan'; $pageSub='Informasi slot waktu yang tersedia untuk pemesanan'; $active='schedule';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="card">
  <h3 style="margin-bottom:16px;"><?=ic('calendar')?> Slot Waktu Tersedia</h3>
  <div class="grid" style="grid-template-columns:repeat(5,1fr);">
    <?php foreach (SLOTS as $s): ?>
    <div class="card" style="text-align:center;padding:18px;"><div style="font-size:20px;color:var(--primary);margin-bottom:8px;"><?=ic('clock')?></div><div style="font-weight:700;font-size:13.5px;"><?=$s?></div></div>
    <?php endforeach; ?>
  </div>
  <div style="margin-top:20px;font-size:13px;color:var(--text-dim);line-height:1.7;">
    Pesanan dengan toko, zona, dan slot waktu yang sama berpotensi digabungkan oleh admin untuk efisiensi rute pengantaran. Proses penggabungan tidak akan menambah waktu tunggu secara berlebihan.
  </div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>