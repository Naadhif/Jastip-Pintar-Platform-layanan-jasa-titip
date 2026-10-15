<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('admin');

$done = $pdo->query("SELECT * FROM orders WHERE status='Selesai'")->fetchAll();
$totalRevenue = array_sum(array_column($done, 'total_fee'));
$avg = count($done) ? round($totalRevenue / count($done)) : 0;

$quarters = [
    ['label'=>'Triwulan 1 (Target)','orders'=>150,'revenue'=>1200000],
    ['label'=>'Triwulan 2 (Target)','orders'=>350,'revenue'=>2800000],
    ['label'=>'Triwulan 3 (Target)','orders'=>700,'revenue'=>5600000],
    ['label'=>'Triwulan 4 (Target)','orders'=>1200,'revenue'=>9600000],
];

$pageTitle='Laporan'; $pageSub='Ringkasan keuangan dan proyeksi usaha'; $active='report';
require __DIR__ . '/../includes/layout_start.php';
?>
<div class="kpi-grid">
  <div class="kpi"><div class="ic-wrap" style="background:#22c55e22;color:#22c55e;"><?=ic('wallet')?></div><div class="num"><?=rupiah($totalRevenue)?></div><div class="lbl">Pendapatan Aktual (Selesai)</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:var(--primary-dim);color:var(--primary2);"><?=ic('cart')?></div><div class="num"><?=count($done)?></div><div class="lbl">Pesanan Selesai</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:#4da3ff22;color:#4da3ff;"><?=ic('receipt')?></div><div class="num"><?=rupiah($avg)?></div><div class="lbl">Rata-rata Biaya Jasa</div></div>
  <div class="kpi"><div class="ic-wrap" style="background:#b366ff22;color:#b366ff;"><?=ic('chart')?></div><div class="num">2.400</div><div class="lbl">Target Pesanan Tahun 1</div></div>
</div>
<div class="card">
  <h3 style="margin-bottom:14px;">Target Proyeksi Tahunan (dari Proposal Usaha)</h3>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Periode</th><th>Target Pesanan</th><th>Target Pendapatan Jasa</th></tr></thead>
      <tbody>
      <?php foreach ($quarters as $q): ?>
      <tr><td><?=$q['label']?></td><td><?=$q['orders']?></td><td><?=rupiah($q['revenue'])?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div style="font-size:12px;color:var(--text-dim);margin-top:10px;">* Angka target merupakan asumsi perencanaan dari proposal usaha.</div>
</div>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>