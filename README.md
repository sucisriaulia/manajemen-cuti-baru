# 🏢 Sistem Manajemen Cuti Karyawan (HR System)

Sistem informasi berbasis web untuk mengelola pengajuan, persetujuan, dan pelacakan cuti karyawan secara digital. Aplikasi ini mendukung alur persetujuan bertingkat (Multi-level Approval) dan validasi cuti yang cerdas.

---

## 🚀 Fitur Unggulan

### 1. Multi-Level User (Role Based Access Control)
* **Admin:** Mengelola User, Divisi, dan Hari Libur.
* **HRD:** Melakukan persetujuan akhir (Final Approval) dan melihat rekapitulasi.
* **Ketua Divisi:** Melakukan verifikasi awal cuti anggota divisinya.
* **Karyawan:** Mengajukan cuti dan melihat riwayat.

### 2. Alur Persetujuan Bertingkat (Workflow)
* Karyawan mengajukan cuti ➝ Masuk ke **Ketua Divisi** (Verifikasi) ➝ Lanjut ke **HRD** (Approval Final) ➝ Selesai.
* Status cuti terpantau secara *real-time* (Menunggu, Acc Ketua, Disetujui, Ditolak).

### 3. Validasi Cuti Cerdas (Smart Validation)
* **Cek Kuota:** Otomatis menolak jika sisa cuti habis.
* **Cek Masa Kerja:** Karyawan baru (< 1 tahun) tidak bisa mengajukan Cuti Tahunan.
* **Validasi Tanggal:**
    * Cuti Tahunan wajib diajukan minimal H-3.
    * Tidak boleh mengajukan di tanggal yang *overlap* (bentrok).
* **Perhitungan Hari Kerja:** Sistem **otomatis melewati** hari Sabtu, Minggu, dan **Hari Libur Nasional** (Tanggal Merah) dalam perhitungan durasi cuti.

### 4. Fitur Pendukung Lainnya
* 📄 **Cetak Surat Izin (PDF):** Surat bukti cuti dapat didownload otomatis setelah status Disetujui Final.
* 📎 **Upload Surat Dokter:** Wajib lampiran bukti untuk Cuti Sakit.
* 📅 **Manajemen Hari Libur:** Admin dapat input tanggal merah agar tidak memotong kuota cuti.
* 👤 **Manajemen Profil:** Update foto profil, nomor HP, dan alamat domisili.
* 🚫 **Status Akun:** Admin dapat menonaktifkan (blokir) akun karyawan.

---

## 🛠️ Teknologi yang Digunakan
* **Framework:** Laravel 10 / 11
* **Bahasa:** PHP 8.2+
* **Database:** MySQL
* **Frontend:** Blade Templating + Tailwind CSS
* **PDF Generator:** Barryvdh/DomPDF

---

## ⚙️ Cara Instalasi (Installation)

Ikuti langkah ini untuk menjalankan proyek di komputer lokal Anda:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/username-anda/nama-repo.git](https://github.com/sucisriaulia/manajemen-cuti-baru.git)
    cd nama-repo
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment**
    Duplikat file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database (`DB_DATABASE`, dll).

4.  **Generate Key & Storage Link**
    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

5.  **Migrasi Database**
    ```bash
    php artisan migrate
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Buka browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo (Default Login)

Gunakan akun berikut untuk pengujian sistem:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@test.com` | `password` |
| **HRD Manager** | `hrd@test.com` | `password` |
| **Ketua Divisi** | `ketuait@example.com` | `password` |
| **Karyawan** | `budi@test.com` | `password` |

---

### 👨‍💻 Author
Dibuat oleh **[Suci Sri Aulia_H071241067]**
*Tugas Final Praktikum Pemrograman Web 2025*