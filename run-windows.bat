@echo off
cd /d "%~dp0"
where php >nul 2>nul
if errorlevel 1 (
 echo PHP tidak ditemukan. Instal XAMPP atau tambahkan PHP ke PATH.
 pause
 exit /b 1
)
start http://localhost:8000
php -S localhost:8000 router.php
