# TODO: Fitur Persetujuan Peminjaman dengan 45 Jam

## Ringkasan Perubahan
- Admin menyetujui → Langsung status "Dipinjam" dengan durasi 45 jam

## Files yang perlu diedit:

### 1. app/Models/Loan.php
- Tambahkan konstanta `LOAN_DURATION_45_HOURS = 45` (jam)
- Update method `approve()` untuk langsung set status BORROWED dengan due_date 45 jam

### 2. app/Http/Controllers/Admin/LoanController.php  
- Method `approve()` perlu dimodifikasi untuk:
  - Set status langsung ke BORROWED
  - Set due_date = now + 45 hours
  - Set loan_date = now

### 3. resources/views/admin/loans/show.blade.php
- Update tampilan tombol persetujuan
- Hapus tombol "Mark as Borrowed" karena sudah digabung dengan approve
- Tampilkan durasi 45 jam dalam deskripsi

### 4. resources/views/admin/loans/index.blade.php
- Update status badge untuk menampilkan "Dipinjam" bukan "Disetujui"
- Update filter jika perlu

## Followup Steps:
1. Test approve button di admin panel
2. Verifikasi due_date tercatat 45 jam dari sekarang
3. Cek stock buku berkurang setelah approval

## Status: COMPLETED ✓

### Files yang sudah diedit:
1. ✓ app/Models/Loan.php - Added 45 hours constant and updated approve() method
2. ✓ app/Http/Controllers/Admin/LoanController.php - Updated approve() to use 45 hours
3. ✓ resources/views/admin/loans/show.blade.php - Updated UI for approve button
4. ✓ resources/views/admin/loans/index.blade.php - Updated filter and status display
5. ✓ resources/views/loans/show.blade.php - Updated duration display for user

