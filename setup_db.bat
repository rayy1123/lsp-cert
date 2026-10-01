@echo off
set PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin;%PATH%

echo 1. Membuat database usk_astro di MySQL (jika belum ada)...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS usk_astro;"

echo 2. Membersihkan cache konfigurasi Laravel...
php artisan config:clear
php artisan cache:clear

echo 3. Menjalankan migrasi database...
php artisan migrate --force

echo 4. Menjalankan seeder data awal LSP...
php artisan db:seed --class=LspSeeder --force

echo.
echo ===========================================
echo Database MySQL usk_astro berhasil disetup!
echo ===========================================
pause
