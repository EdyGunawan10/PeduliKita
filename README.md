```markdown
<div align="center">

# 🤝 PeduliKita
### *Platform Donasi & Penggalangan Dana Transparan*

<img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/SQLite-3-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
<img src="https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
<img src="https://img.shields.io/badge/CSS3-Liquid%20Glass-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3">
<img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">

---

**PeduliKita** adalah platform donasi dan penggalangan dana berbasis web modern yang dirancang untuk menghubungkan para donatur dengan berbagai kampanye sosial secara aman. Sistem ini berfokus penuh pada penyediaan antarmuka yang bersih serta alur kerja yang mengutamakan keterbukaan informasi finansial.

[🚀 Memulai](#-cara-menjalankan-proyek) • [✨ Fitur](#-fitur-sistem) • [🔐 Keamanan](#-sistem-keamanan) • [🗺️ Roadmap](#-rencana-pengembangan-roadmap)

</div>

---

## 🌍 Tentang PeduliKita

Platform ini dikembangkan dengan memprioritaskan tiga pilar utama dalam aktivitas filantropi digital:

*   **❤️ Empathy (Empati):** Memudahkan penemuan kampanye sosial kemanusiaan yang membutuhkan bantuan segera.
*   **🔍 Transparency (Transparansi):** Menampilkan metrik alokasi dana secara terbuka untuk menghindari penyalahgunaan.
*   **🤝 Accountability (Akuntabilitas):** Menyediakan validasi berkas laporan dan bukti transaksi yang dapat dipertanggungjawabkan.

Proyek ini dirancang sebagai cetak biru (*blueprint*) aplikasi penggalangan dana yang komplet, sangat ideal untuk portofolio profesional, proyek akademik, inisiatif sosial, maupun basis pengembangan produksi di masa depan.

---

## ✨ Fitur Sistem

### 👤 1. Sisi Donatur (Donor Features)
*   **Eksplorasi Kampanye:** Menjelajahi, mencari, dan memfilter kampanye berdasarkan kategori serta status urgensi.
*   **Detail Informasi Komplet:** Akses informasi target dana, progres persentase terkini, profil penerima manfaat, lokasi, hingga batas waktu penggalangan.
*   **Sistem Donasi Fleksibel:** Pengisian form donasi secara terstruktur, dukungan opsi donatur hamba Allah (*anonymous*), serta pengunggahan bukti transfer (mendukung format `JPG`, `PNG`, `WEBP`, dan `PDF`).
*   **Dasbor & Resi Digital:** Rekam jejak riwayat donasi personal, pemantauan status verifikasi, dan fitur cetak resi donasi resmi berbasis PDF via browser.

### 👨‍💼 2. Sisi Administrator (Admin Features)
*   **Manajemen Kampanye:** Kontrol CRUD penuh (Buat, Baca, Ubah, Hapus) untuk seluruh kampanye sosial.
*   **Verifikasi Pembayaran:** Tinjau bukti transfer dan perbarui status donasi secara *real-time* (`PENDING`, `VERIFIED`, `REJECTED`).
*   **Pembaruan Kabar (Kabar Terbaru):** Publikasi dokumentasi aktivitas lapangan dan perkembangan terkini berkala kepada donatur pendukung.
*   **Laporan Penggunaan Dana:** Pencatatan log pengeluaran finansial secara detail (jumlah pengeluaran, peruntukan dana, tanggal realisasi).

### 📊 3. Dasbor Transparansi & Ekspor Data
*   **Financial Tracking:** Grafik ringkasan perbandingan target total, jumlah dana terverifikasi, akumulasi biaya logistik terpakai, serta kalkulasi sisa saldo berjalan.
*   **Ekspor Laporan (CSV Engine):** Fitur unduh berkas data transaksi dan laporan keuangan dalam format CSV untuk kebutuhan audit eksternal.

---

## 🏗️ Arsitektur Sistem

PeduliKita mengadopsi arsitektur monolitik ringan yang efisien, responsif, dan mudah dipindahkan antar-server:

```text
┌─────────────────────────────────────────────┐
│                 PeduliKita                  │
├─────────────────────────────────────────────┤
│                                             │
│               Web Interface                 │
│          HTML5 · CSS3 · JavaScript          │
│                      │                      │
│                      ▼                      │
│                 PHP Backend                 │
│                      │                      │
│        ┌─────────────┴─────────────┐        │
│        │                           │        │
│        ▼                           ▼        │
│   Autentikasi &               Manajemen     │
│    Otorisasi                   Donasi       │
│                                             │
│                      │                      │
│                      ▼                      │
│                  SQLite 3                   │
│                                             │
│        Uploads · Reports · Admin Tools      │
└─────────────────────────────────────────────┘

```

---

## 📁 Struktur Direktori Proyek

```text
pedulikita/
├── admin/                  # Panel kontrol manajemen administrator
│   ├── campaigns.php       # Manajemen CRUD kampanye
│   ├── donations.php       # Validasi bukti transfer donasi
│   ├── reports.php         # Penangan laporan audit finansial
│   └── updates.php         # Modul editor update perkembangan
├── assets/                 # Berkas statis pendukung UI
│   ├── css/                # Gaya desain (Liquid Glass Interface)
│   ├── images/             # Ikon dan aset ilustrasi grafik
│   └── js/                 # Logika interaksi Vanilla JavaScript
├── data/                   # Ruang penyimpanan basis data terisolasi
│   └── pedulikita.sqlite   # File database SQLite utama
├── uploads/                # Direktori penyimpanan media (User Generated Content)
│   ├── campaigns/          # Foto spanduk/dokumentasi kampanye
│   └── payment-proofs/     # Berkas dokumen bukti transfer donatur
├── index.php               # Halaman beranda utama
├── router.php              # Mekanisme routing built-in development server
├── run-windows.bat         # Skrip automasi startup Windows OS
├── reset-database.bat      # Skrip automasi reset data demonstrasi
└── README.md               # Dokumentasi utama proyek

```

---

## 🚀 Cara Menjalankan Proyek

### Persyaratan Sistem

Aplikasi ini dirancang menggunakan arsitektur seminimal mungkin tanpa ketergantungan pada *package manager* eksternal. Anda **TIDAK memerlukan** Node.js, npm, Composer, MySQL, maupun Docker. Cukup pastikan runtime berikut aktif:

* **PHP 8.1 atau versi terbaru**
* Ekstensi PHP wajib aktif: `pdo_sqlite`, `sqlite3`, dan `fileinfo`.

Periksa kesiapan PHP melalui terminal Anda:

```bash
php -v

```

### Langkah Menjalankan

#### **Metode A: Menggunakan Skrip Otomatis (Windows)**

Jika Anda menggunakan sistem operasi Windows, cukup klik dua kali pada berkas berkode:

```cmd
run-windows.bat

```

#### **Metode B: Menggunakan PHP Built-in Server (Semua OS)**

1. Buka terminal atau command prompt, lalu masuk ke folder direktori proyek:
```bash
cd pedulikita

```


2. Nyalakan server lokal bawaan PHP dengan memuat router:
```bash
php -S localhost:8000 router.php

```


3. Akses platform melalui browser kesayangan Anda di alamat:
```text
http://localhost:8000

```



#### **Metode C: Menggunakan XAMPP**

1. Salin seluruh folder proyek `pedulikita` ke dalam direktori server lokal XAMPP Anda (umumnya di `C:\xampp\htdocs\pedulikita`).
2. Jalankan modul **Apache** pada panel kontrol XAMPP (Modul MySQL tidak perlu dinyalakan karena sistem menggunakan berkas database berbasis file SQLite).
3. Akses aplikasi melalui tautan berikut:
```text
http://localhost/pedulikita

```



---

## 🔑 Kredensial Akun Demonstrasi

Untuk mempermudah proses pengujian fungsionalitas fitur, Anda dapat masuk menggunakan akun demo berikut:

| Peran | Alamat Email | Kata Sandi |
| --- | --- | --- |
| **👨‍💼 Administrator** | `admin@pedulikita.id` | `Admin123!` |
| **👤 Donatur Terdaftar** | `donor@pedulikita.id` | `Donor123!` |

> 🔄 **Catatan Database Otomatis:**
> Berkas basis data `pedulikita.sqlite` akan dibuat secara otomatis beserta data bawaan (*seeded data*) saat pertama kali aplikasi dibuka di browser. Jika Anda ingin menyetel ulang data demonstrasi seperti semula, jalankan skrip `reset-database.bat` atau hapus file secara manual di dalam folder `data/`.

---

## 🔐 Sistem Keamanan

PeduliKita mengimplementasikan praktik keamanan berlapis untuk menjaga integritas data penggalangan dana:

* **Prepared Statements (PDO):** Proteksi menyeluruh dari ancaman serangan *SQL Injection*.
* **Secure Session Management:** Sistem autentikasi berbasis sesi PHP yang terlindungi.
* **Password Cryptography:** Hashing satu arah kata sandi menggunakan algoritma modern sebelum disimpan.
* **Strict File Validation:** Validasi ganda berkas unggahan bukti transfer berdasarkan ekstensi dan *MIME Type* asli guna mencegah injeksi *malware*.

---

## 🔒 Prosedur Kesiapan Produksi (Deployment Checklist)

Sebelum merilis platform ini ke server publik, pastikan Anda telah melengkapi konfigurasi berikut demi keamanan:

1. **Transport Encryption:** Wajib aktifkan protokol komunikasi aman **HTTPS**.
2. **Database Isolation:** Pindahkan berkas `pedulikita.sqlite` ke direktori luar dari *public web root* agar tidak dapat diunduh langsung via URL internet.
3. **Error Handling:** Nonaktifkan opsi penampilan pesan error (`display_errors = Off`) pada berkas berkode `php.ini` produksi.
4. **Gateway Integration:** Integrasikan dengan API *Official Payment Gateway* berlisensi (seperti Midtrans, Xendit, atau Stripe) untuk otomasi pembukuan dan meminimalkan manipulasi bukti transfer manual.

---

## 🗺️ Rencana Pengembangan (Roadmap)

### Core Platform (Selesai)

* [x] Fitur pencarian dan penjelajahan katalog kampanye.
* [x] Sistem donasi hamba Allah (*anonymous*).
* [x] Unggah dokumen bukti pembayaran dan konfirmasi verifikasi admin.
* [x] Dasbor grafik transparansi dana, riwayat penggunaan dana, dan ekspor laporan CSV.

### Future Improvements (Rencana Lanjutan)

* [ ] Integrasi *Payment Gateway* instan otomatis (QRIS, Virtual Account, Retail Wallet).
* [ ] Sistem notifikasi *real-time* berbasis WhatsApp Gateway dan Email OTP.
* [ ] Fitur otentikasi dua faktor (*Two-Factor Authentication*) untuk keamanan akun administrator.
* [ ] Pemindai otomatis antivirus (*Antivirus Malware Engine*) terintegrasi pada berkas unggahan.

---

📄 **Lisensi**

Proyek ini didistribusikan di bawah lisensi resmi **MIT License**. Anda diizinkan sepenuhnya untuk menyalin, memodifikasi, mendistribusikan, dan menggunakan kode sumber ini untuk keperluan portofolio maupun komersial dengan mencantumkan kredit pemilik asli.

---

**━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━**

### WATERMARK

# **PeduliKita Platform**

### **Developed by EDY GUNAWAN**

### **Donation & Fundraising Architecture**

### **© 2026 All Rights Reserved**

**━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━**