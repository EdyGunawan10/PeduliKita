@echo off
cd /d "%~dp0"
if exist data\pedulikita.sqlite del /q data\pedulikita.sqlite
if exist uploads\proofs\* del /q uploads\proofs\* 2>nul
echo Database berhasil direset. Buka aplikasi untuk membuat data demo kembali.
pause
