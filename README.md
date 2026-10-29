# Dashboard Monitoring Jadwal Sholat Seluruh Indonesia (Live API)

Dashboard monitoring jadwal sholat **38 Ibukota Provinsi Resmi Republik Indonesia** dari Sabang sampai Merauke secara real-time, terintegrasi dengan **Live API MyQuran v2 (Kementerian Agama RI)** berdasarkan standar hisab **MABIMS**.

---

## Fitur Utama

- **Live API Kemenag Terintegrasi:** Data waktu sholat terhubung langsung ke API resmi MyQuran v2 (`api.myquran.com/v2/sholat/jadwal`).
- **38 Ibukota Provinsi Lengkap:** Mencakup seluruh 38 provinsi di Indonesia (WIB, WITA, dan WIT).
- **Waktu Sholat Akurat:** Imsak, Subuh, Dzuhur, Ashar, Maghrib, dan Isya dengan standar hisab MABIMS.
- **Konversi Penanggalan Hijriah Otomatis:** Perhitungan kalender Hijriah otomatis dengan algoritma Julian Day.
- **Jam Real-time 3 Zona Waktu:** Tampilan jam langsung untuk WIB (UTC+7), WITA (UTC+8), dan WIT (UTC+9).
- **Pencarian Instan & Filter Zona:** Cari kota/provinsi tanpa reload (shortcut keyboard: `Ctrl + K` atau `/`), serta filter per zona waktu.
- **Modal Detail Kartu Jadwal:** Popup detail per kota dilengkapi estimasi arah kiblat dan tombol salin ke clipboard.
- **Ekspor Data:** Dukungan unduh jadwal ke format **Microsoft Excel (.xls)** dan **CSV**.
- **Print-Friendly:** Tampilan cetak rapi dan otomatis menyembunyikan navigasi/tombol saat dicetak.
- **Desain Modern:** Islamic theme berbasis Tailwind CSS dengan aksen hijau emerald & putih.

---

## Struktur File Project di `C:\xampp\htdocs\jadwal-sholat`

```text
C:\xampp\htdocs\jadwal-sholat/
├── index.html                  # File utama untuk hosting static (GitHub Pages & Vercel)
├── index.php                   # Versi Single-File PHP + Live API untuk XAMPP lokal
├── vercel.json                 # Konfigurasi routing rewrite untuk Vercel
├── .gitignore                  # Filter file yang diabaikan Git
├── README.md                   # Dokumentasi panduan project
└── jadwal-sholat-indonesia.zip # Arsip ZIP siap pakai
```

> [!NOTE]
> - **`index.html`** adalah file yang **WAJIB di-upload** saat hosting di **GitHub Pages** atau **Vercel** karena layanan hosting tersebut bersifat *static* (hanya menjalankan HTML & JavaScript, tidak menjalankan PHP).
> - **`index.php`** dijalankan di server lokal XAMPP (`http://localhost/jadwal-sholat/`).

---

## Panduan Hosting Ulang ke GitHub & GitHub Pages

### Opsi A: Upload Langsung via Web GitHub (Paling Mudah)

1. Buka [github.com](https://github.com) dan buka repository jadwal sholat Anda.
2. Klik tombol **Add file** > **Upload files**.
3. Tarik dan masukkan file berikut dari folder `C:\xampp\htdocs\jadwal-sholat\`:
   - **`index.html`** *(Paling penting agar web yang tampil di hosting adalah versi terbaru)*
   - `index.php`
   - `vercel.json`
   - `.gitignore`
   - `README.md`
4. Di bagian bawah, tulis pesan commit (misalnya: `Update Live API dan Tampilan Terbaru`) lalu klik **Commit changes**.
5. Tunggu 1–2 menit agar GitHub Pages selesai melakukan deploy.
6. Buka link web GitHub Pages Anda, lalu tekan **`Ctrl + F5`** (Hard Refresh) untuk memuat tampilan baru.

---

### Opsi B: Menggunakan Git Command Line (Terminal)

```bash
# 1. Masuk ke folder project XAMPP
cd C:\xampp\htdocs\jadwal-sholat

# 2. Inisialisasi git (jika belum pernah)
git init

# 3. Tambahkan semua file yang sudah diperbarui
git add .

# 4. Commit perubahan
git commit -m "feat: sinkronisasi live api myquran dan update file hosting github"

# 5. Ubah branch utama ke main
git branch -M main

# 6. Hubungkan ke repository GitHub Anda (ganti USERNAME dan NAMA-REPO)
git remote add origin https://github.com/USERNAME/NAMA-REPO.git

# 7. Push ke GitHub
git push -u origin main --force
```

---

## Memastikan Pengaturan GitHub Pages Sudah Aktif

1. Di repository GitHub Anda, klik tab **Settings**.
2. Di menu sebelah kiri, klik **Pages**.
3. Pada bagian **Build and deployment** > **Branch**:
   - Pilih branch: **`main`** (atau `master`)
   - Pilih folder: **`/(root)`**
   - Klik **Save**.
4. Website akan aktif di alamat: `https://USERNAME.github.io/NAMA-REPO/`
