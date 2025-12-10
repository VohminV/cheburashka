@echo off
set BACKUP_DIR=C:\xampp\htdocs\cheburashka\backups
set DATE=%date:~-4%-%date:~4,2%-%date:~7,2%
set FILE=%BACKUP_DIR%\cheburashka_%DATE%.sql

pg_dump -h localhost -U postgres -d postgres -n public -f "%FILE%"

echo Backup saved: %FILE%
pause