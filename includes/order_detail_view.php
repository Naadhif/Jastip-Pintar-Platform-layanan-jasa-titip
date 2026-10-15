<?php
$idx = array_search($order['status'], STATUS_STEPS);
?>
<div class="card" style="max-width:640px;">
  <div class="flex between" style="margin-bottom:14px;">
    <h3>Pesanan <?=e($order['id'])?></h3>
    <span class="badge <?=statusBadgeClass($order['status'])?>"><?=e($order['status'])?></span>
  </div>
  <?php if ($order['merge_group']): ?>
  <div class="badge badge-shop" style="margin-bottom:14px;"><?=ic('merge')?> Grup <?=e($order['merge_group'])?></div>
  <?php endif; ?>

  <?php if ($order['status']!=='Dibatalkan' && $idx!==false): ?>
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

  <div style="font-size:13.5px;line-height:1.9;margin:14px 0;">
    <?php if ($isAdmin): ?><div><b>Pelanggan:</b> <?=e($order['user_name'])?></div><?php endif; ?>
    <div><b>Toko:</b> <?=e($order['store'])?></div>
    <div><b>Kategori:</b> <?=e(CATEGORIES[$order['category']]['label'] ?? $order['category'])?></div>
    <div><b>Jadwal:</b> <?=e($order['order_date'])?> • <?=e($order['slot'])?></div>
    <div><b>Alamat:</b> <?=e($order['address'])?></div>
<?php if (!empty($order['distance_km'])): ?>
<div><b>Jarak ke Toko:</b> <?=number_format($order['distance_km'],2)?> km (<?=e($order['zone'])?>)</div>
<?php endif; ?>
    <div><b>Mitra:</b> <?=e($order['mitra_name'] ?? '-')?></div>
    <?php if ($order['note']): ?><div><b>Catatan:</b> <?=e($order['note'])?></div><?php endif; ?>
  </div>

  <div class="card" style="padding:14px;background:var(--surface2);">
    <b style="font-size:13px;">Daftar Barang</b>
    <?php foreach ($items as $it): ?>
    <div class="flex between" style="font-size:12.5px;padding:5px 0;"><span><?=e($it['name'])?> x<?=$it['qty']?></span><span style="color:var(--text-dim);"><?=e($it['note'])?></span></div>
    <?php endforeach; ?>
  </div>

  <div class="price-box">
    <div class="price-row"><span>Biaya Jasa</span><span><?=rupiah($order['service_fee'])?></span></div>
    <div class="price-row"><span>Biaya Antar</span><span><?=rupiah($order['zone_fee'])?></span></div>
    <div class="price-row"><span>Biaya Tambahan</span><span><?=rupiah($order['extra_fee'])?></span></div>
    <div class="price-row total"><span>Total Biaya Layanan</span><span><?=rupiah($order['total_fee'])?></span></div>
  </div>

  <?php if ($isAdmin): ?>
  <form method="POST" action="<?=base_url('actions/save_proof_action.php')?>" class="field" style="margin-top:14px;">
    <input type="hidden" name="order_id" value="<?=e($order['id'])?>">
    <label>Bukti Pembelian / Catatan Mitra</label>
    <textarea class="input" name="proof"><?=e($order['proof'])?></textarea>
    <button class="btn btn-outline btn-sm" style="margin-top:8px;" type="submit"><?=ic('upload')?> Simpan Bukti</button>
  </form>
  <?php elseif ($order['proof']): ?>
  <div class="field">
    <label><?=ic('receipt')?> Bukti Pembelian</label>
    <div class="card" style="padding:12px;font-size:13px;background:var(--surface2);"><?=e($order['proof'])?></div>
  </div>
  <?php endif; ?>

  <div class="flex gap10" style="margin-top:16px;">
    <?php if ((!$isAdmin && $order['status']==='Menunggu Konfirmasi') || $isAdmin): ?>
    <?php if ($order['status'] !== 'Dibatalkan' && $order['status'] !== 'Selesai'): ?>
    <form method="POST" action="<?=base_url('actions/cancel_order_action.php')?>" style="flex:1;">
      <input type="hidden" name="order_id" value="<?=e($order['id'])?>">
      <button class="btn btn-danger btn-block" type="submit">Batalkan Pesanan</button>
    </form>
    <?php endif; endif; ?>
    <a class="btn btn-ghost" href="<?=base_url($isAdmin?'admin/orders.php':'user/my_orders.php')?>">Kembali</a>
  </div>
</div>