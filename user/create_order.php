<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin('user');
$u = currentUser();

$extraHead = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />';

$pageTitle='Buat Pesanan'; $pageSub='Pilih toko, lengkapi barang, dan tentukan lokasi pengantaran di peta'; $active='order';
require __DIR__ . '/../includes/layout_start.php';
?>
<form method="POST" action="<?=base_url('actions/create_order_action.php')?>" id="orderForm">
  <div class="order-layout">
    <div>

      <!-- ===== STORE PICKER ===== -->
      <div class="card" style="margin-bottom:20px;">
        <h3 style="margin-bottom:6px;"><?=ic('store')?> Pilih Toko</h3>
        <p style="font-size:12.5px;color:var(--text-dim);margin-bottom:16px;">Pilih dari 33+ toko nyata sekitar kampus UNM, atau masukkan toko lainnya secara manual.</p>
        <div class="store-picker">
          <div class="input-icon-wrap store-search">
            <?=ic('search')?>
            <input class="input" id="storeSearch" placeholder="Cari nama toko..." oninput="filterStoreSearch()">
          </div>
          <div class="store-cat-tabs" id="storeCatTabs">
            <div class="store-cat-tab active" data-cat="semua" onclick="filterStoreCat('semua', this)"><?=ic('dots')?> Semua</div>
            <?php foreach (STORE_CATEGORIES as $key=>$cat): ?>
            <div class="store-cat-tab" data-cat="<?=$key?>" onclick="filterStoreCat('<?=$key?>', this)"><?=ic($cat['icon'])?> <?=e($cat['label'])?></div>
            <?php endforeach; ?>
          </div>
          <div class="store-grid" id="storeGrid">
            <?php foreach (STORES as $s): ?>
            <div class="store-card" data-cat="<?=e($s['category'])?>" data-name="<?=e($s['name'])?>" data-lat="<?=$s['lat']?>" data-lng="<?=$s['lng']?>" onclick="selectStore(this)">
              <div class="sc-ic"><?=ic(STORE_CATEGORIES[$s['category']]['icon'])?></div>
              <div><div class="sc-name"><?=e($s['name'])?></div><div class="sc-addr"><?=e($s['address'])?></div></div>
            </div>
            <?php endforeach; ?>
            <div class="store-card custom" data-cat="semua" data-custom="1" data-name="Toko Lainnya" onclick="selectStore(this)">
              <div class="sc-ic"><?=ic('plus')?></div>
              <div class="sc-name">Toko Lainnya</div>
            </div>
          </div>
          <div class="field" style="margin-top:14px; margin-bottom:0;">
            <label>Toko Terpilih</label>
            <input class="input" id="storeHiddenInput" name="store" placeholder="Pilih toko di atas" readonly required>
          </div>
        </div>
      </div>

      <!-- ===== LOKASI PENGANTARAN (PETA) ===== -->
      <div class="card" style="margin-bottom:20px;">
        <div class="map-toolbar">
          <h3 style="margin:0;"><?=ic('mapPin')?> Lokasi Pengantaran</h3>
          <button type="button" class="btn btn-outline btn-sm" id="useLocationBtn" onclick="useMyLocation()"><?=ic('target')?> Gunakan Lokasi Saya</button>
        </div>
        <p style="font-size:12.5px;color:var(--text-dim);margin-bottom:12px;">Klik pada peta atau geser penanda untuk menentukan titik alamatmu. Biaya antar dihitung otomatis dari jarak ke toko.</p>
        <div id="orderMap" data-lat="<?=CAMPUS_LAT?>" data-lng="<?=CAMPUS_LNG?>"></div>
        <div class="map-legend">
          <span><span class="legend-dot" style="background:#2563eb;"></span> Lokasi Kamu</span>
          <span><span class="legend-dot" style="background:#e8213a;"></span> Lokasi Toko</span>
        </div>
        <div class="loc-status" id="locationStatusText" style="margin-top:10px;"><span class="loc-dot"></span> Lokasi belum ditentukan</div>
        <input type="hidden" id="customerLatInput" name="customer_lat">
        <input type="hidden" id="customerLngInput" name="customer_lng">
        <input type="hidden" id="storeLatInput" name="store_lat">
        <input type="hidden" id="storeLngInput" name="store_lng">
        <input type="hidden" id="distanceKmInput" name="distance_km" value="0">
        <div class="field" style="margin-top:14px; margin-bottom:0;">
          <label>Alamat Lengkap (patokan/nama kos)</label>
          <input class="input" name="address" value="<?=e($u['address'])?>" placeholder="Contoh: Kos Melati, Jl. AP Pettarani No. 12" required>
        </div>
      </div>

      <!-- ===== DETAIL BARANG ===== -->
      <div class="card">
        <h3 style="margin-bottom:16px;">Detail Barang</h3>
        <div class="field">
          <label>Kategori Barang</label>
          <div class="cat-opts">
            <?php foreach (CATEGORIES as $key=>$c): ?>
            <div class="cat-opt <?= $key==='konsumsi'?'active':'' ?>" data-value="<?=$key?>" onclick="setActiveOption('.cat-opt', this, 'categoryInput')"><?=ic($c['icon'])?><div><?=$c['label']?></div></div>
            <?php endforeach; ?>
          </div>
          <input type="hidden" id="categoryInput" name="category" value="konsumsi">
        </div>

        <div class="field">
          <label>Daftar Barang</label>
          <div id="itemRowsWrap">
            <div class="item-row">
              <input class="input" name="items[0][name]" placeholder="Nama barang" oninput="calcPrice()">
              <input class="input" type="number" min="1" value="1" name="items[0][qty]" oninput="calcPrice()">
              <input class="input" name="items[0][note]" placeholder="Catatan (opsional)">
              <button type="button" class="btn-icon" onclick="this.parentElement.remove(); calcPrice();"><?=ic('trash')?></button>
            </div>
          </div>
          <button type="button" class="btn btn-outline btn-sm" onclick="addItemRow()"><?=ic('plus')?> Tambah Barang</button>
        </div>

        <div class="field" style="margin-bottom:0;"><label>Catatan Tambahan (opsional)</label><textarea class="input" name="note"></textarea></div>
      </div>
    </div>

    <div>
      <div class="card" style="margin-bottom:18px;">
        <h3 style="margin-bottom:14px;">Jadwal Layanan</h3>
        <div class="field"><label>Tanggal</label>
          <select class="input" name="order_date">
            <?php for ($i=0;$i<7;$i++): $d=date('Y-m-d', strtotime("+$i day")); ?>
            <option value="<?=$d?>"><?=$d?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="field" style="margin-bottom:0;"><label>Slot Waktu</label>
          <div class="slot-opts">
            <?php foreach (SLOTS as $i=>$s): ?>
            <div class="slot-opt <?=$i===0?'active':''?>" data-value="<?=$s?>" onclick="setActiveOption('.slot-opt', this, 'slotInput')"><?=$s?></div>
            <?php endforeach; ?>
          </div>
          <input type="hidden" id="slotInput" name="slot" value="<?=SLOTS[0]?>">
        </div>
      </div>

      <div class="card">
        <h3 style="margin-bottom:10px;"><?=ic('receipt')?> Rincian Biaya</h3>
        <div class="price-box">
          <div class="price-row"><span><?=ic('route')?> Jarak ke Toko</span><span id="priceDistanceLabel">Belum ditentukan</span></div>
          <div class="price-row"><span>Biaya Jasa Titip</span><span id="priceService">Rp8.000</span></div>
          <div class="price-row"><span>Biaya Antar (otomatis)</span><span id="priceZone">Rp0</span></div>
          <div class="price-row"><span>Biaya Tambahan (>5 item)</span><span id="priceExtra">Rp0</span></div>
          <div class="price-row total"><span>Total Biaya Layanan</span><span id="priceTotal">Rp8.000</span></div>
          <div style="font-size:11.5px;color:var(--text-dim);margin-top:8px;">* Harga barang dibayar terpisah sesuai struk toko. 1 km pertama gratis ongkos antar.</div>
        </div>
        <button class="btn btn-primary btn-block" style="margin-top:16px;" type="submit"><?=ic('check')?> Konfirmasi Pesanan</button>
      </div>
    </div>
  </div>
</form>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php require __DIR__ . '/../includes/layout_end.php'; ?>