# Product Requirements Document (PRD)

**Nama Sistem:** Integrated Coffee Shop Back-Office & Performance Management System  
**Status:** Perancangan Sistem (Skripsi)  
**Target Pengguna:** Owner & Staff Coffee Shop  
**Tujuan:** Migrasi manajemen operasional dari Spreadsheet ke sistem web terpusat untuk meningkatkan integritas data, transparansi keuangan, dan objektivitas penilaian kinerja karyawan.

---

## 1. Analisis Peran Pengguna (User Roles)

### A. Owner (Super Admin)
- **Manajemen Staff:** Menambah, mengedit, dan menghapus data staff.
- **Pengaturan Gaji:** Menentukan rate gaji harian yang berbeda untuk setiap staff.
- **Konfigurasi KPI:** Membuat variabel KPI (misal: Kebersihan, Up-selling, Disiplin) dan menentukan bobot poinnya.
- **Audit Keuangan:** Mengakses laporan pemasukan (Cash, QRIS, Merchant) dan pengeluaran secara detail berdasarkan filter tanggal dan shift.
- **Validasi:** Meninjau unggahan nota pengeluaran dari staff.

### B. Staff (Operator)
- **Presensi:** Melakukan absen masuk dan keluar.
- **Input Operasional:** Mengisi data total penjualan (rekapitulasi) di akhir shift.
- **Manajemen Kas Kecil:** Menginput pengeluaran untuk restock/kebutuhan darurat disertai unggah foto nota.
- **Self-Assessment KPI:** Mengisi laporan pencapaian KPI harian sesuai variabel yang ditentukan Owner.

---

## 2. Spesifikasi Fitur Utama

### A. Modul Keuangan (Back-Office Finance)
1. **Multichannel Revenue Input:** Form rekapitulasi harian untuk memisahkan saldo dari:
   - Tunai (Cash)
   - QRIS (Dine-in)
   - Merchant (GrabFood, GoFood, ShopeeFood)
2. **Expenditure Tracking:** Pencatatan pengeluaran yang memotong saldo kasir secara otomatis.
3. **Digital Evidence:** Integrasi unggah file gambar (nota/struk) pada setiap transaksi pengeluaran.
4. **Shift Reconcilation:** Perhitungan otomatis saldo kasir yang harus diserahterimakan antar shift.

### B. Modul SDM & Payroll (HR & Performance)
1. **Dynamic Payroll Engine:** Perhitungan gaji otomatis berdasarkan (Jumlah Hari Masuk x Rate Gaji Individual).
2. **KPI Scoring System:** Sistem akumulasi poin berdasarkan variabel yang diinput staff dan divalidasi owner.
3. **Incentive Calculator:** Logika otomatis untuk menambah bonus pada gaji jika skor KPI mencapai ambang batas (threshold) tertentu.

---

## 3. Alur Data (Data Flow)
1. **Pemasukan:** Data dari POS (eksternal) direkap harian ke sistem ini -> Masuk ke Database Keuangan.
2. **Pengeluaran:** Staff input -> Upload Nota -> Saldo Kasir berkurang -> Owner Validasi.
3. **Gaji:** Absensi + Data KPI -> Proses Kalkulasi -> Laporan Gaji Bulanan/Mingguan.

---

## 4. Kebutuhan Non-Fungsional
- **Security:** Role-Based Access Control (RBAC).
- **Responsivitas:** Dapat diakses dengan baik melalui perangkat mobile (untuk absen dan input staff).
- **Audit Trail:** Setiap perubahan data penting dicatat (siapa, kapan, apa).
