<?php
require_once __DIR__ . '/includes/functions.php';
if (currentUser()) redirect(base_url(currentUser()['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
?>
<!DOCTYPE html>
<html class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JastipPintar | Titip Belanja Digital untuk Mahasiswa & Anak Kos</title>
<meta name="description" content="Platform pemesanan dan pelacakan jasa titip belanja berbasis digital untuk mahasiswa dan anak kos.">
<link rel="icon" type="image/svg+xml" href="<?= favicon_data_uri() ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/landing.css') ?>">
<script>(function(){ document.documentElement.className = localStorage.getItem('jp_theme') || 'dark'; })();</script>
</head>
<body class="lp-body">

<div class="lp-blob lp-blob-1"></div>
<div class="lp-blob lp-blob-2"></div>
<div class="lp-blob lp-blob-3"></div>

<!-- ===== NAVBAR ===== -->
<nav class="lp-nav">
  <div class="lp-nav-inner">
    <div class="brand" style="font-size:18px;"><div class="logo-box" style="width:34px;height:34px;"><?= ic('logo') ?></div> JastipPintar</div>
    <div class="lp-nav-links">
      <a href="#fitur">Fitur</a><a href="#cara-kerja">Cara Kerja</a><a href="#testimoni">Testimoni</a><a href="#tentang">Tentang</a>
    </div>
    <div class="flex gap10">
      <button class="btn-icon" onclick="toggleTheme()"><?= ic('sun') ?></button>
      <a class="btn btn-outline btn-sm" href="<?= base_url('login.php') ?>">Masuk</a>
      <a class="btn btn-primary btn-sm" href="<?= base_url('register.php') ?>">Daftar</a>
    </div>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section class="lp-hero">
  <div>
    <div class="lp-eyebrow"><?= ic('sparkle') ?> Platform Titip Belanja Digital #1 untuk Mahasiswa</div>
    <h1>Belanja kebutuhan kos,<br>jadi <span class="hl">lebih praktis</span><br>dengan JastipPintar</h1>
    <p>Titip belanja kebutuhan harian, pilih jadwal yang kamu mau, pantau status pesanan secara langsung, dan nikmati rincian biaya yang transparan.</p>
    <div class="lp-hero-cta">
      <a class="btn btn-primary" href="<?= base_url('register.php') ?>"><?= ic('rocket') ?> Mulai Sekarang</a>
      <a class="btn btn-outline" href="#fitur">Lihat Fitur <?= ic('chevRight') ?></a>
    </div>
    <div class="lp-trust">
      <div class="lp-avatars">
        <div class="av" style="background:#e8213a;">NF</div>
        <div class="av" style="background:#4da3ff;">AM</div>
        <div class="av" style="background:#22c55e;">MP</div>
        <div class="av" style="background:#b366ff;">+</div>
      </div>
      <div>
        <div class="lp-stars"><?= str_repeat(ic('star'), 5) ?></div>
        <div class="lp-trust-text">Dipercaya <b>50+ mahasiswa</b> dalam tahap uji coba</div>
      </div>
    </div>
  </div>

  <!-- Floating visual stack -->
  <div class="lp-visual">
    <div class="lp-visual-ring"></div>

    <div class="lp-float-card lp-card-main">
      <div class="tag-row">
        <div class="store-ic"><?= ic('store') ?></div>
        <span class="badge badge-ship">Dalam Perjalanan</span>
      </div>
      <div style="font-weight:700; font-size:14.5px;">Indomaret AP Pettarani</div>
      <div style="font-size:12px; color:var(--text-dim); margin-bottom:6px;">3 barang • Slot 13:00–15:00</div>
      <div class="prog-track"><div class="prog-fill"></div></div>
      <div class="flex between" style="font-size:11.5px; color:var(--text-dim);">
        <span>Menunggu</span><span>Selesai</span>
      </div>
      <div class="flex between" style="margin-top:14px; padding-top:14px; border-top:1px solid var(--border);">
        <span style="font-size:12px; color:var(--text-dim);">Total Biaya Jasa</span>
        <b style="font-size:15px;">Rp10.000</b>
      </div>
    </div>

    <div class="lp-float-card lp-card-badge lp-badge-1">
      <div class="b-ic"><?= ic('checkCircle') ?></div>
      <div><div style="font-size:13px; font-weight:700;">98% Tepat Waktu</div><div style="font-size:10.5px; color:var(--text-dim);">Rating layanan</div></div>
    </div>

    <div class="lp-float-card lp-card-badge lp-badge-2">
      <div class="b-ic"><?= ic('merge') ?></div>
      <div><div style="font-size:13px; font-weight:700;">Rute Digabung</div><div style="font-size:10.5px; color:var(--text-dim);">Lebih efisien</div></div>
    </div>

    <div class="lp-float-card lp-card-badge lp-badge-3">
      <div class="b-ic"><?= ic('wallet') ?></div>
      <div><div style="font-size:13px; font-weight:700;">Biaya Transparan</div><div style="font-size:10.5px; color:var(--text-dim);">Tanpa biaya tersembunyi</div></div>
    </div>
  </div>
</section>

<!-- ===== MARQUEE ===== -->
<div class="lp-marquee-wrap">
  <div class="lp-marquee-track">
    <?php
    $marqueeItems = ['Indomaret','Alfamart','Toko Kelontong Kampus','Fotokopi UNM','Apotek Sehat','Warung Bu Siti','Indomaret','Alfamart','Toko Kelontong Kampus','Fotokopi UNM','Apotek Sehat','Warung Bu Siti'];
    foreach (array_merge($marqueeItems,$marqueeItems) as $m): ?>
    <div class="lp-marquee-item"><?= ic('store') ?> <?= e($m) ?></div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ===== FITUR (BENTO GRID) ===== -->
<section class="lp-section" id="fitur">
  <div class="lp-head">
    <div class="eyebrow-sm">Fitur Unggulan</div>
    <h2>Semua yang kamu butuhkan dalam satu platform</h2>
    <p>Dirancang khusus untuk menjawab masalah mahasiswa dan anak kos yang punya jadwal padat.</p>
  </div>
  <div class="lp-bento">
    <div class="lp-bento-item big">
      <div class="lp-bento-deco"></div>
      <div class="lp-bento-icon"><?= ic('merge') ?></div>
      <div>
        <h3>Penggabungan Pesanan Otomatis</h3>
        <p>Pesanan dari toko, zona, dan jadwal yang sama digabungkan dalam satu rute untuk efisiensi waktu dan biaya pengantaran.</p>
      </div>
    </div>
    <div class="lp-bento-item">
      <div class="lp-bento-icon"><?= ic('cart') ?></div>
      <h3>Titip Belanja Harian</h3>
      <p>Konsumsi, kebersihan, hingga perlengkapan kuliah.</p>
    </div>
    <div class="lp-bento-item">
      <div class="lp-bento-icon"><?= ic('calendar') ?></div>
      <h3>Jadwal Fleksibel</h3>
      <p>Pilih slot waktu sesuai jadwal kuliahmu.</p>
    </div>
    <div class="lp-bento-item wide">
      <div class="lp-bento-icon"><?= ic('truck') ?></div>
      <h3>Pelacakan Status Real-time</h3>
      <p>Pantau pesanan dari menunggu konfirmasi hingga selesai, kapan saja.</p>
    </div>
    <div class="lp-bento-item">
      <div class="lp-bento-icon"><?= ic('receipt') ?></div>
      <h3>Biaya Transparan</h3>
      <p>Rincian biaya ditampilkan sebelum konfirmasi.</p>
    </div>
    <div class="lp-bento-item">
      <div class="lp-bento-icon"><?= ic('shield') ?></div>
      <h3>Bukti Pembelian</h3>
      <p>Setiap pesanan dilengkapi bukti transaksi.</p>
    </div>
  </div>
</section>

<!-- ===== CARA KERJA ===== -->
<section class="lp-section" id="cara-kerja">
  <div class="lp-head" style="margin:0 auto 54px; text-align:center;">
    <div class="eyebrow-sm">Cara Kerja</div>
    <h2>Lima langkah mudah menuju belanja praktis</h2>
  </div>
  <div class="lp-steps">
    <?php
    $steps = [
      ['1','cart','Buat Pesanan','Isi daftar barang & pilih jadwal'],
      ['2','receipt','Konfirmasi Biaya','Terima rincian biaya transparan'],
      ['3','store','Mitra Berbelanja','Mitra membelikan pesananmu'],
      ['4','truck','Dalam Perjalanan','Pantau status secara langsung'],
      ['5','checkCircle','Selesai','Barang diterima + bukti pembelian'],
    ];
    foreach ($steps as [$n,$icon,$t,$d]): ?>
    <div class="lp-step">
      <div class="lp-step-num"><?= $n ?></div>
      <h4><?= $t ?></h4>
      <p><?= $d ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== IMPACT STATS ===== -->
<section class="lp-section" style="padding-top:0;">
  <div class="lp-stat-grid">
    <div class="lp-stat-card"><div class="lp-stat-num">2.400+</div><div class="lp-stat-lbl">Target Pesanan Tahun 1</div></div>
    <div class="lp-stat-card"><div class="lp-stat-num">Rp19,2jt</div><div class="lp-stat-lbl">Proyeksi Pendapatan</div></div>
    <div class="lp-stat-card"><div class="lp-stat-num">50</div><div class="lp-stat-lbl">Target Pengguna Uji Coba</div></div>
    <div class="lp-stat-card"><div class="lp-stat-num">5</div><div class="lp-stat-lbl">Status Pelacakan Real-time</div></div>
  </div>
</section>

<!-- ===== TESTIMONI ===== -->
<section class="lp-section" id="testimoni">
  <div class="lp-head">
    <div class="eyebrow-sm">Apa Kata Mereka</div>
    <h2>Dipercaya oleh mahasiswa sekitar kampus</h2>
  </div>
  <div class="lp-testi-grid">
    <?php
    $testis = [
      ['NF','#e8213a','Nurul F.','Mahasiswa Teknik Komputer','Sangat membantu di tengah jadwal praktikum yang padat. Status pesanan jelas banget!'],
      ['AF','#4da3ff','Aditya F.','Anak Kos, Tanpa Kendaraan','Gak perlu pusing cari ojek buat beli kebutuhan kos. Biaya juga transparan dari awal.'],
      ['MN','#22c55e','Maulina P.','Mahasiswa Aktif Organisasi','Fitur jadwalnya pas banget sama waktu luang saya. Prosesnya juga cepat.'],
    ];
    foreach ($testis as [$ini,$color,$name,$role,$quote]): ?>
    <div class="lp-testi-card">
      <div class="quote-ic"><?= ic('quote') ?></div>
      <p>"<?= e($quote) ?>"</p>
      <div class="lp-testi-user">
        <div class="av" style="background:<?= $color ?>;"><?= $ini ?></div>
        <div><div class="lp-testi-name"><?= e($name) ?></div><div class="lp-testi-role"><?= e($role) ?></div></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="lp-section" id="tentang">
  <div class="lp-cta-split">
    <div class="lp-cta-left">
      <h2>Siap belanja tanpa ribet?</h2>
      <p>Bergabunglah sebagai pengguna untuk memesan, atau sebagai mitra untuk mendapatkan penghasilan tambahan.</p>
      <div class="hero-cta">
        <a class="btn" style="background:#fff; color:var(--primary); font-weight:700;" href="<?= base_url('register.php') ?>"><?= ic('bolt') ?> Daftar sebagai Pengguna</a>
        <a class="btn btn-outline" style="border-color:#fff; color:#fff;" href="<?= base_url('login.php') ?>">Masuk ke Akun</a>
      </div>
    </div>
    <div class="lp-cta-right">
      <div class="lp-cta-mock">
        <div class="row"><span><?= ic('layers') ?> Status</span><span>Selesai</span></div>
        <div class="big-num">Rp8.000</div>
        <div class="row"><span>Biaya Jasa Titip</span><span><?= ic('checkCircle') ?></span></div>
        <div style="height:1px; background:rgba(255,255,255,.25); margin:12px 0;"></div>
        <div class="row"><span>Pesanan</span><span>JP10245</span></div>
        <div class="row"><span>Jadwal</span><span>13:00–15:00</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="lp-footer">
  <div class="lp-footer-grid">
    <div>
      <div class="brand" style="margin-bottom:14px;"><div class="logo-box" style="width:34px;height:34px;"><?= ic('logo') ?></div> JastipPintar</div>
      <p style="font-size:13px; color:var(--text-dim); line-height:1.7; max-width:260px;">Platform titip belanja digital untuk mahasiswa dan anak kos di sekitar kampus.</p>
    </div>
    <div>
      <h4>Produk</h4>
      <a href="#fitur">Fitur</a><a href="#cara-kerja">Cara Kerja</a><a href="<?= base_url('register.php') ?>">Daftar</a>
    </div>
    <div>
      <h4>Perusahaan</h4>
      <a href="#tentang">Tentang Kami</a><a href="#testimoni">Testimoni</a>
    </div>
    <div>
      <h4>Mulai</h4>
      <a href="<?= base_url('login.php') ?>">Masuk</a><a href="<?= base_url('register.php') ?>">Daftar Akun</a>
    </div>
  </div>
  <div class="lp-footer-bottom">
    Proposal Usaha Mata Kuliah Kewirausahaan — Kelompok 3, Program Studi Teknik Komputer, Universitas Negeri Makassar &copy; 2026
  </div>
</footer>

<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>