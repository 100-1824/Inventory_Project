@echo off
echo ============================================
echo Export MySQL Database for InfinityFree
echo ============================================
echo.

REM Check if .env file exists and uses MySQL
findstr /C:"DB_CONNECTION=mysql" .env >nul
if errorlevel 1 (
    echo ERROR: .env file is not configured for MySQL!
    echo Please run setup_local_mysql.bat first or ensure DB_CONNECTION=mysql in .env
    pause
    exit /b 1
)

echo Reading database credentials from .env file...
echo.

REM Parse .env file for database credentials (basic parsing)
for /f "tokens=1,2 delims==" %%a in (.env) do (
    if "%%a"=="DB_HOST" set DB_HOST=%%b
    if "%%a"=="DB_DATABASE" set DB_DATABASE=%%b
    if "%%a"=="DB_USERNAME" set DB_USERNAME=%%b
    if "%%a"=="DB_PASSWORD" set DB_PASSWORD=%%b
)

echo Database: %DB_DATABASE%
echo Username: %DB_USERNAME%
echo Host: %DB_HOST%
echo.

REM Check if mysqldump is available
where mysqldump >nul 2>&1
if errorlevel 1 (
    echo ERROR: mysqldump command not found!
    echo.
    echo Please ensure MySQL is installed and in your PATH.
    echo Common locations:
    echo - C:\xampp\mysql\bin
    echo - C:\Program Files\MySQL\MySQL Server 8.0\bin
    echo.
    echo Or add MySQL bin directory to your system PATH.
    pause
    exit /b 1
)

echo Exporting database to database_export_mysql.sql...
echo.

REM Export database
if "%DB_PASSWORD%"=="" (
    mysqldump -h %DB_HOST% -u %DB_USERNAME% %DB_DATABASE% > database_export_mysql.sql
) else (
    mysqldump -h %DB_HOST% -u %DB_USERNAME% -p%DB_PASSWORD% %DB_DATABASE% > database_export_mysql.sql
)

if errorlevel 1 (
    echo ERROR: Database export failed!
    echo Please check your database credentials and try again.
    pause
    exit /b 1
)

echo.
echo ============================================
echo Export Successful!
echo ============================================
echo.
echo The file 'database_export_mysql.sql' has been created.
echo.
echo This file is ready to import into InfinityFree via phpMyAdmin.
echo.
echo File size:
dir database_export_mysql.sql | find "database_export_mysql.sql"
echo.
echo ============================================
pause
