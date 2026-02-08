@echo off
title Unique Hardware - Inventory Management
color 0A

:: Set the project root directory
cd /d "%~dp0"

:: Set path to local PHP
set "PHP_BINARY=%~dp0bin\php\php.exe"

:: Add local PHP to PATH so artisan serves work correctly
set "PATH=%~dp0bin\php;%PATH%"

echo.
echo  =========================================
echo     UNIQUE HARDWARE - INVENTORY SYSTEM
echo             (Portable Mode)
echo  =========================================
echo.
echo  Starting the application...
echo.

:: Check if PHP exists
if not exist "%PHP_BINARY%" (
    color 0C
    echo  ERROR: PHP not found at %PHP_BINARY%
    echo  Please ensure the 'bin\php' folder exists.
    pause
    exit
)

:: Start the PHP development server in background
start /min cmd /c ""%PHP_BINARY%" artisan serve"

:: Wait for server to start
timeout /t 3 /nobreak > nul

:: Open the application in default browser
start http://127.0.0.1:8000

echo.
echo  Application is running at: http://127.0.0.1:8000
echo.
echo  Press any key to STOP the server and exit...
pause > nul

:: Kill PHP server processes
taskkill /f /im php.exe > nul 2>&1

echo.
echo  Server stopped. Goodbye!
