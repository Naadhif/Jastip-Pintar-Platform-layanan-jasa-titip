// ===== THEME TOGGLE =====
(function(){
  const saved = localStorage.getItem('jp_theme') || 'dark';
  document.documentElement.className = saved;
})();
function toggleTheme(){
  const html = document.documentElement;
  html.className = html.className === 'dark' ? 'light' : 'dark';
  localStorage.setItem('jp_theme', html.className);
}

// ===== SIDEBAR MOBILE =====
function toggleSidebar(){
  document.querySelector('.sidebar')?.classList.toggle('open');
}

// ===== ORDER FORM: dynamic items =====
let itemIndex = document.querySelectorAll('.item-row').length || 1;

function addItemRow(){
  const wrap = document.getElementById('itemRowsWrap');
  if (!wrap) return;
  const row = document.createElement('div');
  row.className = 'item-row';
  row.innerHTML = `
    <input class="input" name="items[${itemIndex}][name]" placeholder="Nama barang" oninput="calcPrice()">
    <input class="input" type="number" min="1" value="1" name="items[${itemIndex}][qty]" oninput="calcPrice()">
    <input class="input" name="items[${itemIndex}][note]" placeholder="Catatan (opsional)">
    <button type="button" class="btn-icon" onclick="this.parentElement.remove(); calcPrice();">&times;</button>`;
  wrap.appendChild(row);
  itemIndex++;
  calcPrice();
}

function setActiveOption(groupSel, el, hiddenInputId){
  document.querySelectorAll(groupSel).forEach(o => o.classList.remove('active'));
  el.classList.add('active');
  const hidden = document.getElementById(hiddenInputId);
  if (hidden) hidden.value = el.dataset.value;
  calcPrice();
}

// ===== PRICE CALCULATION (distance-based) =====
function setText(id, val){ const el = document.getElementById(id); if (el) el.textContent = val; }
function fmtRupiah(n){ return 'Rp' + Math.round(n).toLocaleString('id-ID'); }

function calcPrice(){
  const distEl = document.getElementById('distanceKmInput');
  if (!distEl) return;
  const distanceKm = parseFloat(distEl.value || 0);
  const serviceBase = 8000;
  const freeKm = 1;
  const ratePerKm = 2000;
  let distanceFee = 0;
  if (distanceKm > freeKm) {
    distanceFee = Math.ceil(((distanceKm - freeKm) * ratePerKm) / 500) * 500;
  }
  let totalQty = 0;
  document.querySelectorAll('.item-row input[name$="[qty]"]').forEach(inp => {
    totalQty += parseInt(inp.value || 0);
  });
  const extra = totalQty > 5 ? (totalQty - 5) * 500 : 0;
  const total = serviceBase + distanceFee + extra;

  setText('priceService', fmtRupiah(serviceBase));
  setText('priceDistanceLabel', distanceKm > 0 ? distanceKm.toFixed(2) + ' km' : 'Belum ditentukan');
  setText('priceZone', fmtRupiah(distanceFee));
  setText('priceExtra', fmtRupiah(extra));
  setText('priceTotal', fmtRupiah(total));
}

// ===== STORE PICKER =====
function filterStoreCat(cat, el){
  document.querySelectorAll('.store-cat-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  applyStoreFilter();
}
function filterStoreSearch(){ applyStoreFilter(); }
function applyStoreFilter(){
  const activeTab = document.querySelector('.store-cat-tab.active');
  const cat = activeTab ? activeTab.dataset.cat : 'semua';
  const search = (document.getElementById('storeSearch')?.value || '').toLowerCase().trim();
  document.querySelectorAll('.store-card').forEach(card => {
    const isCustom = card.dataset.custom === '1';
    const matchCat = cat === 'semua' || card.dataset.cat === cat || isCustom;
    const matchSearch = search === '' || card.dataset.name.toLowerCase().includes(search);
    const show = matchCat && (isCustom ? true : matchSearch);
    card.style.display = show ? 'flex' : 'none';
  });
}

function selectStore(el){
  document.querySelectorAll('.store-card').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  const input = document.getElementById('storeHiddenInput');
  if (!input) return;

  if (el.dataset.custom === '1'){
    input.value = '';
    input.readOnly = false;
    input.placeholder = 'Ketik nama toko lainnya...';
    input.focus();
    document.getElementById('storeLatInput').value = '';
    document.getElementById('storeLngInput').value = '';
    document.getElementById('distanceKmInput').value = 2;
    clearStoreMarker();
    calcPrice();
  } else {
    input.readOnly = true;
    input.value = el.dataset.name;
    input.placeholder = '';
    const lat = parseFloat(el.dataset.lat);
    const lng = parseFloat(el.dataset.lng);
    document.getElementById('storeLatInput').value = lat;
    document.getElementById('storeLngInput').value = lng;
    setStoreLocation(lat, lng, el.dataset.name);
  }
}

// ===== MAP (Leaflet + OpenStreetMap) =====
let orderMap, customerMarker, storeMarker, routeLine;

function initOrderMap(){
  const mapDiv = document.getElementById('orderMap');
  if (!mapDiv || typeof L === 'undefined') return;
  const centerLat = parseFloat(mapDiv.dataset.lat) || -5.1614;
  const centerLng = parseFloat(mapDiv.dataset.lng) || 119.4257;

  orderMap = L.map('orderMap', { scrollWheelZoom: false }).setView([centerLat, centerLng], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(orderMap);

  setTimeout(() => orderMap.invalidateSize(), 300);

  orderMap.on('click', function(e){
    setCustomerLocation(e.latlng.lat, e.latlng.lng);
  });
}

function setCustomerLocation(lat, lng){
  const latInput = document.getElementById('customerLatInput');
  const lngInput = document.getElementById('customerLngInput');
  if (!latInput || !orderMap) return;
  latInput.value = lat.toFixed(7);
  lngInput.value = lng.toFixed(7);

  if (customerMarker) {
    customerMarker.setLatLng([lat, lng]);
  } else {
    customerMarker = L.marker([lat, lng], { draggable: true }).addTo(orderMap);
    customerMarker.bindPopup('Lokasi Kamu').openPopup();
    customerMarker.on('dragend', function(e){
      const pos = e.target.getLatLng();
      setCustomerLocation(pos.lat, pos.lng);
    });
  }

  const statusEl = document.getElementById('locationStatusText');
  if (statusEl) {
    statusEl.innerHTML = '<span class="loc-dot"></span> Lokasi pengantaran ditentukan';
    statusEl.classList.add('loc-set');
  }
  updateRouteAndDistance();
}

function setStoreLocation(lat, lng, name){
  if (!orderMap) return;
  if (storeMarker) {
    storeMarker.setLatLng([lat, lng]);
  } else {
    storeMarker = L.circleMarker([lat, lng], {
      radius: 9, color: '#fff', weight: 2, fillColor: '#e8213a', fillOpacity: 1
    }).addTo(orderMap);
  }
  storeMarker.bindPopup(name);
  updateRouteAndDistance();
}

function clearStoreMarker(){
  if (storeMarker && orderMap) { orderMap.removeLayer(storeMarker); storeMarker = null; }
  if (routeLine && orderMap) { orderMap.removeLayer(routeLine); routeLine = null; }
}

function updateRouteAndDistance(){
  const cLat = parseFloat(document.getElementById('customerLatInput')?.value);
  const cLng = parseFloat(document.getElementById('customerLngInput')?.value);
  const sLat = parseFloat(document.getElementById('storeLatInput')?.value);
  const sLng = parseFloat(document.getElementById('storeLngInput')?.value);
  const distEl = document.getElementById('distanceKmInput');
  if (!distEl) return;

  if (!isNaN(cLat) && !isNaN(sLat) && !isNaN(cLng) && !isNaN(sLng)) {
    const dist = haversineKm(cLat, cLng, sLat, sLng);
    distEl.value = dist.toFixed(2);

    if (routeLine) orderMap.removeLayer(routeLine);
    routeLine = L.polyline([[cLat, cLng], [sLat, sLng]], { color: '#e8213a', weight: 3, dashArray: '6,8' }).addTo(orderMap);
    orderMap.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
  }
  calcPrice();
}

function haversineKm(lat1, lon1, lat2, lon2){
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2) ** 2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLon/2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  return R * c;
}

const locBtnIcon = '<svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="3"/><line x1="12" y1="1.5" x2="12" y2="4.5"/><line x1="12" y1="19.5" x2="12" y2="22.5"/><line x1="1.5" y1="12" x2="4.5" y2="12"/><line x1="19.5" y1="12" x2="22.5" y2="12"/></svg> Gunakan Lokasi Saya';

function useMyLocation(){
  const btn = document.getElementById('useLocationBtn');
  if (!navigator.geolocation){ alert('Browser tidak mendukung geolokasi.'); return; }
  if (btn) { btn.disabled = true; btn.innerHTML = 'Mencari lokasi...'; }

  navigator.geolocation.getCurrentPosition(function(pos){
    setCustomerLocation(pos.coords.latitude, pos.coords.longitude);
    orderMap.setView([pos.coords.latitude, pos.coords.longitude], 15);
    if (btn) { btn.disabled = false; btn.innerHTML = locBtnIcon; }
  }, function(err){
    alert('Tidak dapat mengambil lokasi otomatis (' + err.message + '). Silakan klik pada peta untuk menentukan lokasi secara manual.');
    if (btn) { btn.disabled = false; btn.innerHTML = locBtnIcon; }
  }, { enableHighAccuracy: true, timeout: 10000 });
}

// ===== FORM VALIDATION =====
function validateOrderForm(e){
  const latInput = document.getElementById('customerLatInput');
  if (latInput && !latInput.value) {
    e.preventDefault();
    alert('Silakan tentukan lokasi pengantaran pada peta terlebih dahulu.');
    document.getElementById('orderMap')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return false;
  }
}

// ===== INIT ON LOAD =====
document.addEventListener('DOMContentLoaded', function(){
  calcPrice();
  initOrderMap();

  const params = new URLSearchParams(window.location.search);
  const cat = params.get('cat');
  if (cat) {
    const tab = document.querySelector('.store-cat-tab[data-cat="' + cat + '"]');
    if (tab) filterStoreCat(cat, tab);
  }

  const form = document.getElementById('orderForm');
  if (form) form.addEventListener('submit', validateOrderForm);
});