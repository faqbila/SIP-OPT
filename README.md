# 🌾 Sistem Informasi Peramalan & Visualisasi Serangan OPT (SIP-OPT) BBPPTP Medan

> **Platform Pemantauan, Visualisasi Geografis, dan Peramalan Serangan Organisme Pengganggu Tanaman (OPT) Berbasis ARIMA**  
> Balai Besar Perbenihan dan Proteksi Tanaman Perkebunan (BBPPTP) Medan

---

## 📌 Gambaran Umum

**SIP-OPT BBPPTP Medan** adalah sistem informasi berbasis web yang dirancang untuk mendukung pegawai dan petugas tata usaha/fungsional di lingkungan BBPPTP Medan dalam memantau, menganalisis, serta memprediksi tingkat serangan Organisme Pengganggu Tanaman (OPT).

Sistem ini mengintegrasikan data historis serangan OPT dari tahun **2018 hingga 2025** di seluruh wilayah terdampak di Pulau Sumatera. Dengan dilengkapi metode statistik **ARIMA (AutoRegressive Integrated Moving Average)** dan visualisasi spasial berbasis peta (GIS), platform ini membantu instansi dalam mengambil langkah mitigasi perlindungan tanaman secara presisi dan terukur.

---

## ✨ Fitur Utama

### 🔐 1. Autentikasi Pegawai & Hak Akses
* **Login Terproteksi:** Pengamanan sesi (*session control*) untuk memastikan hanya pegawai terotorisasi yang dapat mengakses data rekapitulasi dan alat peramalan.
* **Manajemen Pengguna:** Pengolahan data akun pengguna (Tambah, Edit, Hapus User/Admin).

### 📊 2. Dashboard Analytics & Grafik Interaktif
* **Rekapitulasi Multi-Kriteria:** Analisis tren serangan berdasarkan:
  * Wilayah (Kabupaten & Provinsi se-Sumatera)
  * Jenis Komoditas Perkebunan (misal: Kelapa Sawit, Kopi, Kakao, dll.)
  * Jenis OPT (Hama & Penyakit)
* **Visualisasi Komparatif:** Tampilan grafik berupa **Diagram Batang (Bar Chart)** dan **Diagram Garis (Line Chart)** untuk memantau fluktuasi serangan dari waktu ke waktu.

### 🗺️ 3. Peta Visualisasi Spasial (GIS Sumatera)
* **Peta Tematik Berat Serangan:** Visualisasi berbasis peta interaktif yang menampilkan tingkat keparahan/berat serangan OPT per provinsi di Pulau Sumatera.
* **Integrasi GeoJSON:** Pemetaan batas wilayah geografis (*boundary shape*) untuk klasifikasi zona merah, kuning, dan hijau secara intuitif.

### 📈 4. Peramalan (Forecasting) Berbasis ARIMA
* **Predictive Analytics:** Algoritma time-series ARIMA yang memproses data historis serangan (2018–2025) untuk memproyeksikan estimasi luasan/keparahan serangan di periode mendatang.
* **Export Hasil Forecasting:** Kemampuan mengekspor grafik dan tabel peramalan ke format dokumen laporan (PDF/Excel).

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

* **Core Web Backend:** PHP / Python (Flask / FastAPI - `forecast-api`)
* **Machine Learning / Statistics:** Python (`statsmodels`, `pandas`, `numpy`, `scikit-learn` untuk model ARIMA)
* **Database:** MySQL / MariaDB
* **Frontend UI:** HTML5, CSS3, JavaScript, Chart.js / Highcharts
* **Geospatial & Mapping:** Leaflet.js / OpenLayers dengan data format `.json` / `.geojson`
* **Data Interchange:** REST API / JSON (`indonesia-province-simple.json`, `indonesia.json`)
* **Cloud Platform (PaaS):** Railway (untuk hosting microservice `forecast-api` secara fleksibel)

---

## 📂 Struktur Folder Proyek Saat Ini (Current Legacy)

Berikut adalah petak struktur direktori proyek saat ini:

```text
OPT BBPPTP MEDAN/
├── __pycache__/                  # Cache kompilasi Python
├── forecast-api/                 # Layanan API Microservice Peramalan ARIMA
├── hasil/                        # Direktori penyimpanan output laporan
├── old_data/                     # Berkas arsip/data lama
├── secure.file/                  # Berkas penyimpanan aman
├── static/                       # Aset statis (CSS, JS, Gambar)
├── uploads/                      # Berkas unggahan dokumen/lampiran
├── vendor/                       # Third-party library PHP (Composer)
│
├── DATA OPT WILKER 2020-2025-share.xlsx  # Dataset Excel historis
├── indonesia.json                # Data batas wilayah GIS Indonesia
├── indonesia-province-simple.json# Data peta batas provinsi Sumatera
│
├── Procfile                      # File konfigurasi pemicu aplikasi di PaaS Railway
├── requirements.txt              # Daftar dependensi library Python
├── *.php / *.py                  # Skrip kontroler, login, grafik, forecasting, & ekspor
└── README.md                     # Dokumentasi proyek
```

---

## 💡 Rekomendasi Restrukturisasi Folder (Saran Perapihan)

Untuk meningkatkan keterbacaan kode (*clean code*) dan mempermudah pemeliharaan sistem ke depan, disarankan untuk merestrukturisasi folder menjadi pola arsitektur **MVC / Modular**:

```text
SIP-OPT-BBPPTP/
├── app/                          # Logika Utama Aplikasi
│   ├── Controllers/              # Skrip pemroses (login, export, forecast)
│   ├── Models/                   # Query database & manipulasi data
│   └── Views/                    # Tampilan UI (HTML/PHP templates)
├── api/                          # Backend Service / Python ARIMA Engine
│   └── forecast_service.py       # Engine peramalan ARIMA
├── assets/                       # Asset publik
│   ├── css/                      # Stylesheet
│   ├── js/                       # Script JS (Chart, Map, Ajax)
│   ├── geojson/                  # File JSON batas peta wilayah
│   └── img/                      # Logo & gambar background
├── data/                         # Master Data & File Excel Historis
├── uploads/                      # Uploads & Hasil PDF Laporan
├── config/                       # Koneksi database & environment variable
└── index.php                     # Entry point utama
```

---

## 🚀 Panduan Instalasi Lokal

1. **Clone Repositori**
   ```bash
   git clone https://github.com/faqbila/SIP-OPT.git
   cd "OPT BBPPTP MEDAN"
   ```

2. **Persiapan Database**
   * Impor berkas basis data SQL ke phpMyAdmin / MySQL Client Anda.
   * Pastikan tabel `serangan`, `opt`, `komoditas`, `kabupaten`, `provinsi`, dan `users` telah ter-import dengan benar.

3. **Jalankan Aplikasi Web Utama**
   * Pindahkan folder ke lingkungan web server (misal: `C:\xampp\htdocs\OPT BBPPTP MEDAN`).
   * Akses sistem melalui peramban: `http://localhost/OPT BBPPTP MEDAN/login.php`.

---

## ☁️ Deployment `forecast-api` (Microservice ARIMA) ke Railway (PaaS)

Layanan peramalan ARIMA (`forecast-api`) dapat di-deploy secara terpisah ke **Railway** (Platform-as-a-Service) agar dapat diakses kapan saja oleh web utama tanpa membebani web server lokal/shared hosting.

### 1. Persiapan Berkas Deployment
Pastikan di dalam direktori `forecast-api` atau *root folder* sudah terdapat file pendukung berikut:

* **`requirements.txt`** (Menentukan library Python yang dibutuhkan Railway):
  ```text
  flask
  gunicorn
  pandas
  numpy
  statsmodels
  scikit-learn
  ```

* **`Procfile`** (Memberitahu Railway cara menjalankan skrip API Python):
  ```text
  web: gunicorn app:app
  ```
  *(Sesuaikan `app:app` dengan nama file utama API Python Anda, contoh: `main:app` jika nama filenya `main.py`)*

### 2. Langkah Deployment di Railway
1. Buka [Railway.app](https://railway.app/) dan buat akun menggunakan akun **GitHub**.
2. Klik tombol **New Project** > Pilih **Deploy from GitHub repo**.
3. Hubungkan ke repositori **`faqbila/SIP-OPT`**.
4. Jika `forecast-api` berada di dalam subfolder, buka menu **Settings** pada dashboard Railway Anda:
   * Masuk ke bagian **Root Directory** dan isi dengan: `/forecast-api`.
5. Railway akan secara otomatis mendeteksi lingkungan Python, menginstall `requirements.txt`, dan menjalankan perintah dari `Procfile`.
6. Masuk ke tab **Networking** > Klik **Generate Domain** untuk mendapatkan URL publik (contoh: `https://sip-opt-forecast-production.up.railway.app`).

### 3. Menghubungkan Web PHP ke API Railway
Buka file integrasi forecasting di PHP (misal: `forecast.php` atau `api_config.php`), lalu ubah URL endpoint API ke domain publik Railway:

```php
// Sebelum (Lokal)
// $api_url = "http://127.0.0.1:5000/predict";

// Sesudah (Railway PaaS)
$api_url = "https://sip-opt-forecast-production.up.railway.app/predict";
```

---

## 📄 Hak Cipta & Lisensi

Pengembangan sistem ditujukan untuk **Balai Besar Perbenihan dan Proteksi Tanaman Perkebunan (BBPPTP) Medan**.

Hak cipta dilindungi undang-undang. Penggunaan dan pendistribusian ulang diatur oleh ketentuan internal instansi.