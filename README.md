<div align="center">

# 🛒 JASTIP PINTAR
### *Smart Personal Shopper & Batch Delivery Ecosystem*

[![Status](https://img.shields.io/badge/Status-Active_Development-success?style=for-the-badge&logo=git&logoColor=white)]()
[![Tech Stack](https://img.shields.io/badge/Stack-Modern_Web-blue?style=for-the-badge&logo=codeforces&logoColor=white)]()
[![License](https://img.shields.io/badge/License-MIT-purple?style=for-the-badge)]()

*Solusi cerdas, praktis, dan efisien untuk kebutuhan belanja titipan dan kurir personal di lingkungan kampus serta area urban.*

</div>

---

## 📌 Deskripsi Singkat Proyek
**Jastip Pintar** adalah aplikasi berbasis teknologi yang dirancang untuk mengoptimalkan layanan jasa titip dan kurir personal. Platform ini menargetkan efisiensi transaksi, manajemen pesanan terjadwal, serta pengelompokan pengiriman (*batch delivery*) untuk memudahkan mahasiswa, penghuni kos, maupun masyarakat umum dalam memesan kebutuhan harian secara praktis, cepat, dan terorganisir.

---

## 📖 Tentang Proyek

Platform digital ini hadir sebagai solusi atas tingginya mobilitas masyarakat urban dan akademisi yang sering kali terkendala waktu untuk membeli kebutuhan pokok, makanan, atau keperluan mendesak lainnya secara langsung. 

Dengan mengusung konsep **Scheduled Orders** (Pemesanan Terjadwal) dan **Batch Delivery** (Pengiriman Kolektif/Kelompok), aplikasi ini tidak hanya memudahkan pengguna (*user*) dalam memesan barang, tetapi juga mengoptimalkan rute perjalanan bagi para *runner* (penyedia jasa jastip) sehingga lebih hemat waktu, tenaga, dan biaya operasional.

---

## ✨ Fitur Unggulan

<div align="center">

| Fitur | Deskripsi | Status |
| :--- | :--- | :---: |
| 🛍️ **Smart Ordering** | Form pemesanan interaktif dengan detail item, estimasi harga, dan titik *drop-off* yang akurat. | ✅ Ready |
| 📦 **Batch Delivery** | Sistem pengelompokan rute pengiriman berdasarkan lokasi kos/kampus terdekat untuk efisiensi maksimal. | ✅ Ready |
| 🔔 **Real-Time Tracking** | Pemantauan status pesanan secara langsung (Mulai dari *Order Created*, *Shopping*, hingga *Delivered*). | 🔄 On Progress |
| 📊 **Dashboard Analitik** | Rekapitulasi pesanan harian/mingguan bagi pengelola jastip untuk memantau pendapatan dan volume order. | ✅ Ready |
| 💬 **Integrated Chat** | Komunikasi langsung antara pemesan dan kurir untuk konfirmasi ketersediaan barang di toko/supermarket. | 📅 Planned |

</div>

---

## 🛠️ Arsitektur & Teknologi

Proyek ini dibangun menggunakan *tech stack* modern yang handal, cepat, dan mudah dikembangkan (*scalable*):

* **Frontend:** HTML5, CSS3, JavaScript (ES6+), Tailwind CSS / Modern UI Components.
* **Backend:** PHP / Laravel Framework (RESTful API & Controller Architecture).
* **Database:** MySQL / SQLite untuk manajemen relasi data pengguna dan transaksi.
* **Tools & Environment:** Git, Visual Studio Code, Postman, Laragon / Docker.

---

## 📂 Struktur Direktori Proyek

Berikut adalah gambaran umum struktur folder dari repositori ini:

```text
jastip-pintar/
│
├── 📂 app/                  # Logika inti aplikasi & Controller backend
├── 📂 database/             # Migrasi database dan seeder
├── 📂 public/               # Aset publik (CSS, JS, Gambar, Icons)
├── 📂 resources/            # Tampilan antarmuka (Views / Blade / Components)
├── 📂 routes/               # Konfigurasi rute web & API
├── 📄 .env.example          # Contoh variabel konfigurasi environment
├── 📄 composer.json         # Konfigurasi dependensi PHP
├── 📄 package.json          # Konfigurasi dependensi Frontend & build tools
└── 📄 README.md             # Dokumentasi proyek (File ini)
```

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

Bagi Anda yang ingin menjalankan atau berkontribusi pada proyek ini secara lokal, ikuti langkah-langkah di bawah ini:

### 1. Prasyarat Sistem
Pastikan komputer Anda telah terinstal beberapa software berikut:
* PHP (Versi 8.1 atau terbaru)
* Composer
* Node.js & NPM
* Git
* MySQL / MariaDB (via Laragon, XAMPP, atau Docker)

### 2. Langkah Instalasi

1. **Clone repository ini ke komputer lokal Anda:**
   ```bash
   git clone https://github.com/Naadhif/jastip-pintar.git
   ```

2. **Masuk ke direktori proyek:**
   ```bash
   cd jastip-pintar
   ```

3. **Instal dependensi PHP (Backend):**
   ```bash
   composer install
   ```

4. **Instal dependensi JavaScript (Frontend):**
   ```bash
   npm install
   ```

5. **Salin file konfigurasi lingkungan (.env):**
   ```bash
   cp .env.example .env
   ```

6. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

7. **Konfigurasi Database:**
   Buka file `.env`, lalu sesuaikan konfigurasi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=jastip_pintar_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

8. **Jalankan migrasi database:**
   ```bash
   php artisan migrate
   ```

9. **Jalankan server lokal:**
   ```bash
   # Jalankan server backend
   php artisan serve

   # (Di terminal terpisah) Jalankan asset watcher jika menggunakan Vite/Mix
   npm run dev
   ```

Akses aplikasi melalui browser di alamat: `http://127.0.0.1:8000`

---

## 💡 Cara Penggunaan Singkat

1. **Registrasi / Login:** Masuk ke halaman utama dan buat akun baru sebagai *Customer* (Pemesan) atau *Runner* (Kurir Jastip).
2. **Buat Pesanan:** Masukkan detail barang yang ingin dititipkan beserta catatan toko/supermarket tujuan.
3. **Proses Batching:** Sistem akan mencocokkan jadwal dan rute terdekat agar kurir dapat mengambil pesanan secara kolektif.
4. **Selesai:** Barang diantar langsung ke titik lokasi Anda.

---

## 👥 Kontributor & Pembuat

Proyek ini dikembangkan dengan penuh dedikasi oleh:

* **Naadhif** — *Lead Developer & Architect*

---

## 📄 Lisensi

Proyek ini bersifat open-source dan berada di bawah lisensi [MIT License](LICENSE). Silakan gunakan, modifikasi, dan kembangkan sesuai kebutuhan Anda!

---
<div align="center">
  <i>Dibuat dengan ❤️ untuk efisiensi aktivitas harian kampus dan masyarakat urban.</i>
</div>
