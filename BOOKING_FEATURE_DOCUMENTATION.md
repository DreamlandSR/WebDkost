# Dokumentasi Fitur Kelola Booking

## Gambaran Umum
Fitur **Kelola Booking** memungkinkan admin aplikasi WebDkost untuk mengelola data pemesanan kamar dengan antarmuka yang interaktif dan responsif.

## Struktur File

### Controller
- **File**: `app/Http/Controllers/BookingController.php`
- **Method Utama**:
  - `index()` - Menampilkan list semua booking dengan filter dan search
  - `create()` - Menampilkan form untuk membuat booking baru
  - `store()` - Menyimpan booking baru ke database
  - `show()` - Menampilkan detail booking (JSON API)
  - `edit()` - Menampilkan form untuk edit booking
  - `update()` - Mengupdate data booking
  - `destroy()` - Menghapus booking

### View
- **File**: `resources/views/dashboard/booking/index.blade.php`
- **Fitur**:
  - Tabel responsif dengan pagination
  - Filter berdasarkan status booking
  - Search berdasarkan nama pengguna atau nomor kamar
  - Modal untuk tambah, edit, dan detail booking
  - Modal konfirmasi sebelum menghapus

### Routes
Tambahan di `routes/web.php`:
```php
Route::get('/dashboard/booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('/dashboard/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/dashboard/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/dashboard/booking/{id_booking}', [BookingController::class, 'show'])->name('booking.show');
Route::get('/dashboard/booking/{id_booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
Route::put('/dashboard/booking/{id_booking}', [BookingController::class, 'update'])->name('booking.update');
Route::delete('/dashboard/booking/{id_booking}', [BookingController::class, 'destroy'])->name('booking.destroy');
```

## Fitur Utama

### 1. List Booking
- Menampilkan tabel dengan kolom: No, Nama Penyewa, Kamar, Status, Durasi, Tgl Mulai, Biaya/Bulan
- Pagination otomatis untuk 10 data per halaman
- Badge status dengan warna berbeda untuk setiap status:
  - **Pending**: Merah (#ef4444)
  - **Aktif**: Hijau (#00a669)
  - **Selesai**: Biru (#0284c7)
  - **Dibatalkan**: Kuning (#d97706)

### 2. Filter & Search
- **Filter Status**: Dropdown untuk mengfilter berdasarkan status booking
- **Search**: Input teks untuk mencari berdasarkan nama pengguna atau nomor kamar
- Form otomatis di-submit saat filter berubah

### 3. Modal Tambah Booking
Formulir untuk membuat booking baru dengan field:
- Nama Penyewa (dropdown dari tabel users)
- Kamar (dropdown dari tabel kamar)
- Tanggal Mulai Sewa (date picker)
- Durasi Sewa dalam Bulan (number, 1-24 bulan)
- Biaya Bulanan (currency input)
- Status Booking (radio buttons: pending, aktif, selesai, dibatalkan)

**Validasi**:
- Semua field required
- Email, no_telepon dari user harus ada di tabel users
- Kamar harus ada di tabel kamar
- Durasi sewa minimal 1 bulan, maksimal 24 bulan

### 4. Modal Detail Booking
Menampilkan informasi lengkap booking dalam format card dengan layout responsif:
- Nama Penyewa
- Email & No. Telepon Penyewa
- Nomor Kamar
- Status Booking
- Durasi Sewa
- Periode Sewa (Tgl Mulai - Tgl Akhir)
- Biaya Bulanan

### 5. Modal Edit Booking
Form untuk mengupdate data booking dengan struktur sama seperti modal tambah.

### 6. Modal Hapus Booking
Konfirmasi sebelum menghapus booking dengan peringatan visual.

### 7. Paginasi
- Menampilkan total data dan range yang sedang ditampilkan
- Tombol Kembali/Selanjutnya untuk navigasi antar halaman
- Disabled state jika berada di halaman pertama/terakhir

## Styling & User Interface

### Design Pattern
- Mengikuti design system yang sama dengan fitur Kelola Kamar
- Konsisten dengan gradient colors (#00a669 - #008a57)
- Modal dengan border-radius 14px dan shadow effect
- Input fields dengan border 1.5px dan focus state berwarna hijau

### Responsive Design
- Tabel responsive dengan horizontal scroll di mobile
- Modal untuk UI yang lebih baik di semua ukuran layar
- Spacing dan padding yang konsisten
- Terelaksi di breakpoint 768px

### Interaksi
- Hover effects pada tombol action
- Smooth transitions pada form elements
- Auto-select style untuk radio buttons (pill style)
- Alert notifications untuk success/error messages

## Integrasi Database

### Relasi Model
- **Booking** → **User** (belongsTo)
- **Booking** → **Kamar** (belongsTo)
- **User** → **Booking** (hasMany)
- **Kamar** → **Booking** (hasMany)

### Field Database
Tabel `booking` menggunakan field:
- `id_booking` (Primary Key)
- `id_user` (Foreign Key ke users)
- `id_kamar` (Foreign Key ke kamar)
- `tgl_booking` (Tanggal pemesanan)
- `expired_at` (Tanggal expire)
- `durasi_sewa_bulan` (Durasi sewa)
- `tgl_mulai_sewa` (Tanggal mulai)
- `tgl_akhir_sewa` (Tanggal akhir)
- `total_biaya_bulanan` (Biaya per bulan)
- `status_booking` (Status: pending, aktif, selesai, dibatalkan)

## Cara Menggunakan

### Akses Halaman
```
URL: /dashboard/booking
Akses: Admin hanya
```

### Tambah Booking Baru
1. Klik tombol "Tambah Booking" di pojok atas kanan
2. Isi semua field yang required (ditandai *)
3. Pilih penyewa, kamar, tanggal, durasi, dan status
4. Klik "Simpan"

### Edit Booking
1. Klik tombol "Edit" pada row booking yang ingin diubah
2. Update field yang diperlukan
3. Klik "Perbarui"

### Lihat Detail Booking
1. Klik tombol "Detail" pada row booking
2. Modal akan menampilkan informasi lengkap

### Hapus Booking
1. Klik tombol "Hapus" pada row booking
2. Konfirmasi di modal
3. Klik "Hapus Sekarang"

### Filter & Search
1. Gunakan dropdown "Filter status" untuk filter by status
2. Gunakan input search untuk mencari by nama pengguna atau kamar
3. Form akan auto-submit saat ada perubahan

## Validasi & Error Handling

- **Required Fields**: Semua field dalam form harus diisi
- **Data Validation**: Server-side validation di controller
- **Error Messages**: Ditampilkan dalam alert notification
- **Success Messages**: Notifikasi hijau saat operasi berhasil
- **Database Integrity**: Foreign key constraints untuk user dan kamar

## Notes

- Tanggal akhir sewa otomatis dihitung dari tanggal mulai + durasi bulan - 1 hari
- Status default untuk booking baru bisa disesuaikan dengan requirement bisnis
- Expired_at diset sama dengan tanggal mulai sewa saat membuat booking
- Perlu tambahan fitur untuk manage tagihan yang terkait dengan booking

## Future Enhancements

1. Bulk delete booking
2. Export/Import booking data
3. Calendar view untuk visualisasi booking
4. Automatic status update based on dates
5. Email notification kepada pengguna
6. Chart untuk analytics booking trends
7. Integration dengan payment system
