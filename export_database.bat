@echo off
echo ============================================
echo Exporting SQLite Database to SQL Format
echo ============================================
echo.

REM Set up PHP path (using bundled PHP from NativePHP)
set PHP_PATH=bin\php\php.exe
if not exist "%PHP_PATH%" (
    echo Looking for system PHP...
    where php >nul 2>&1
    if errorlevel 1 (
        echo ERROR: PHP not found!
        echo Cannot export database without PHP.
        pause
        exit /b 1
    )
    set PHP_PATH=php
)

REM Check if database exists
if not exist "database\database.sqlite" (
    echo ERROR: database\database.sqlite not found!
    echo Make sure you have data in your SQLite database before exporting.
    pause
    exit /b 1
)

echo Using PHP: %PHP_PATH%
echo.

REM Export using PHP script
%PHP_PATH% export_sqlite_to_mysql.php
if errorlevel 1 (
    echo.
    echo ERROR: Database export failed!
    pause
    exit /b 1
)

echo.
pause
