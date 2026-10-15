-- =========================================================
-- JASTIPPINTAR DATABASE SCHEMA
-- =========================================================
CREATE DATABASE IF NOT EXISTS jastippintar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jastippintar;

-- ===================== TABLE: users =====================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===================== TABLE: mitra ======================
CREATE TABLE mitra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===================== TABLE: orders =====================
CREATE TABLE orders (
  id VARCHAR(20) PRIMARY KEY,
  user_id INT NOT NULL,
  category VARCHAR(30) NOT NULL,
  store VARCHAR(150) NOT NULL,
  address VARCHAR(255) NOT NULL,
  zone VARCHAR(20) NOT NULL,
  order_date DATE NOT NULL,
  slot VARCHAR(30) NOT NULL,
  note TEXT,
  status VARCHAR(30) NOT NULL DEFAULT 'Menunggu Konfirmasi',
  merge_group VARCHAR(20) DEFAULT NULL,
  mitra_id INT DEFAULT NULL,
  proof TEXT,
  service_fee INT NOT NULL DEFAULT 0,
  zone_fee INT NOT NULL DEFAULT 0,
  extra_fee INT NOT NULL DEFAULT 0,
  total_fee INT NOT NULL DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_mitra FOREIGN KEY (mitra_id) REFERENCES mitra(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ================== TABLE: order_items ===================
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(20) NOT NULL,
  name VARCHAR(150) NOT NULL,
  qty INT NOT NULL DEFAULT 1,
  note VARCHAR(255) DEFAULT NULL,
  CONSTRAINT fk_item_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================= TABLE: order_history ==================
CREATE TABLE order_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(20) NOT NULL,
  status VARCHAR(30) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_hist_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===================== SEED: mitra =======================
INSERT INTO mitra (name, phone, active) VALUES
('Kak Rian', '0813xxxxxxxx', 1),
('Kak Sari', '0812xxxxxxxx', 1);

-- Catatan: akun admin & pengguna demo dibuat lewat seed.php
-- (lihat bagian bawah) agar password ter-hash dengan benar.