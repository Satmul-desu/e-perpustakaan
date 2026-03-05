# TODO: Renovasi Dashboard Admin Perpustakaan

## Tahap 1: Edit DashboardController
- [x] 1.1 Ubah statistik dari Order ke Loan
- [x] 1.2 Tambah statistik buku overdue, buku tersedia, anggota perpustakaan

## Tahap 2: Edit dashboard.blade.php
- [x] 2.1 Ubah "Total Pesanan" → "Total Peminjaman" 
- [x] 2.2 Ubah "Pesanan Pending" → "Menunggu Persetujuan"
- [x] 2.3 Hapus kartu "Sedang Diproses" (sudah diganti dengan statistic lain)
- [x] 2.4 Ubah "Rata-rata Pesanan" → "Rata-rata Peminjaman"
- [x] 2.5 Tambah kartu "Buku Terlambat"
- [x] 2.6 Tambah kartu "Buku Tersedia"
- [x] 2.7 Tambah kartu "Total Anggota"
- [x] 2.8 Perbaiki data tables & charts dari Order ke Loan

## Tahap 3: Perbaiki dashboard.blade.php - Data Tables & Charts
- [x] 3.1 Ubah "Pesanan Terbaru" → "Peminjaman Terbaru"
- [x] 3.2 Ganti $recentOrders dengan $recentLoans
- [x] 3.3 Ganti $topProducts dengan $topBooks
- [x] 3.4 Update chart labels dari "Pendapatan" → "Peminjaman"
- [x] 3.5 Ganti $revenueChart dengan $loansChart
- [x] 3.6 Ganti $monthlySales dengan $monthlyLoans
- [x] 3.7 Ganti $salesByCategory dengan $loansByCategory
- [x] 3.8 Update Quick Actions links
- [x] 3.9 Tambah section "Anggota Terlambat"
- [x] 3.10 Fix Activity Log dari payment ke loan status

## Tahap 4: Update Views - Remove Price, Change Headers
- [x] 4.1 products/create.blade.php - Hapus harga, header gelap
- [x] 4.2 products/edit.blade.php - Hapus harga, header gelap
- [x] 4.3 products/index.blade.php - Hapus kolom harga, header gelap
- [x] 4.4 categories/index.blade.php - Header gelap
- [x] 4.5 categories/create.blade.php - Header gelap
- [x] 4.6 categories/edit.blade.php - Header gelap
- [x] 4.7 users/index.blade.php - Ubah ke Anggota, header gelap, hapus kolom aksi

## Tahap 5: Update Layout Sidebar
- [x] 5.1 Ubah "Pesanan" → "Peminjaman"
- [x] 5.2 Ubah "Produk" → "Buku"
- [x] 5.3 Ubah "Pengguna" → "Anggota"

## Tahap 6: Testing & Verifikasi
- [ ] 6.1 Cek tampilan dashboard
- [ ] 6.2 Cek data yang ditampilkan benar

