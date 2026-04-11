# 🏍️ Sistem Peminjaman Motor

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![MySQL Version](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production-success.svg)]()

> Sistem manajemen peminjaman motor berbasis web dengan fitur lengkap untuk admin, petugas, dan peminjam.

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Database](#-struktur-database)
- [Instalasi](#-instalasi)
- [Cara Penggunaan](#-cara-penggunaan)
- [Screenshot](#-screenshot)
- [Contributing](#-contributing)
- [Lisensi](#-lisensi)

## 🎯 Tentang Project

Sistem Peminjaman Motor adalah aplikasi web yang digunakan untuk mengelola proses peminjaman motor secara digital. Aplikasi ini memungkinkan pengguna untuk menyewa motor, admin untuk mengelola data motor, dan petugas untuk memproses peminjaman.

### 👥 Role Pengguna

| Role | Hak Akses |
|------|-----------|
| **Admin** | Mengelola semua data (motor, kategori, user, peminjaman) |
| **Petugas** | Memproses peminjaman dan pengembalian |
| **Peminjam** | Melihat motor, melakukan peminjaman, melihat riwayat |

## ✨ Fitur Utama

### 🔐 Autentikasi
- Login multi-role (Admin, Petugas, Peminjam)
- Registrasi untuk peminjam baru
- Logout system

### 🏍️ Manajemen Motor (Admin & Petugas)
- CRUD data motor
- Upload gambar motor
- Kategori motor (Matic, Sport, Trail, Bebek, Skuter, Classic)
- Filter berdasarkan kategori
- Pencarian motor

### 📝 Manajemen Peminjaman
- Peminjam dapat menyewa motor
- Admin/petugas dapat menyetujui peminjaman
- Proses pengembalian dengan hitung denda
- Riwayat peminjaman per user
- Status peminjaman (Pending, Disetujui, Dipinjam, Selesai, Ditolak)

### 📊 Dashboard
- Statistik peminjaman
- Grafik peminjaman bulanan
- Motor terbaru
- Peminjaman terbaru

### 📧 Contact Management
- Form kontak untuk pengunjung
- Admin dapat membaca dan membalas pesan
- Status pesan (Unread, Read, Replied)

### 📜 Log Activity
- Mencatat semua aktivitas user
- Filter berdasarkan role
- Pencarian log

## 🛠 Teknologi yang Digunakan

### Backend
- **PHP 8.0+** - Bahasa pemrograman utama
- **MySQL 5.7+** - Database management system
- **MySQLi** - Database driver

### Frontend
- **HTML5** - Struktur halaman
- **CSS3** - Styling
- **JavaScript** - Interaktivitas
- **Bootstrap 5** - Framework CSS
- **Font Awesome 6** - Icon library
- **jQuery** - JavaScript library
- **DataTables** - Tabel dinamis
- **AOS** - Scroll animation

### Tools
- **XAMPP / Laragon** - Local server
- **Git** - Version control
- **GitHub** - Repository hosting

## 📊 Struktur Database

### Tabel-tabel utama:

```sql
-- users - Data pengguna
-- barang - Data motor
-- kategori - Kategori motor
-- peminjaman - Data peminjaman
-- contacts - Pesan kontak
-- log_aktivitas - Log aktivitas
