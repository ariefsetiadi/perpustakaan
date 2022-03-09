## Thanks To

-   [x] <a href="https://laravel.com/" target="_blank">Laravel</a>
-   [x] <a href="https://www.php.net/" target="_blank">PHP</a>
-   [x] <a href="https://www.mysql.com/" target="_blank">MySQL</a>
-   [x] <a href="https://getcomposer.org/" target="_blank">Composer</a>
-   [x] <a href="https://yajrabox.com/docs/laravel-datatables/master/installation" target="_blank">Yajra DataTables</a>
-   [x] <a href="https://realrashid.github.io/sweet-alert/" target="_blank">Real Rashid Sweet Alert</a>
-   [x] <a href="https://sweetalert2.github.io/" target="_blank">Sweet Alert 2</a>
-   [x] <a href="https://fontawesome.com/" target="_blank">Font Awesome</a>

## Title

Content Management System - Perpustakaan

## Description

Content Management System - Perpustakaan : Web aplikasi sederhana untuk transaksi peminjaman koleksi perpustakaan dibangun dengan laravel versi 8

## Requirements

-   [x] Composer >= 2.9.1
-   [x] PHP >= 7.3
-   [x] MySql

## How To Install

-   [x] Buka Terminal / Command Prompt / Git Bash
-   [x] Clone repo, **git clone https://github.com/ariefsetiadi/perpustakaan.git**
-   [x] Setelah clone selesai, masuk ke folder **perpustakaan**
-   [x] Jalankan **composer install**
-   [x] Duplicate atau rename file **.env.example** ke **.env**
-   [x] Buka file **.env** dan ganti,
    -   **APP_NAME** -> tidak wajib
    -   **DB_CONNECTION** -> sesuaikan dengan koneksi mysql anda
    -   **DB_HOST** -> sesuaikan dengan host mysql anda
    -   **DB_PORT** -> sesuaikan dengan port mysql anda
    -   **DB_DATABASE** -> sesuaikan dengan nama database mysql anda
    -   **DB_USERNAME** -> sesuaikan dengan username mysql anda
    -   **DB_PASSWORD** -> sesuaikan dengan password mysql anda
-   [x] Jalankan **php artisan migrate** untuk migrasi semua table
-   [x] Terakhir, jalankan **php artisan db:seed** untuk insert faker data user ke database (admin dan petugas/officer)
