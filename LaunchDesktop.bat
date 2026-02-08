@echo off
title Unique Hardware - Desktop Launcher
color 0B

:: Set the project root directory
cd /d "%~dp0"

echo.
echo  =========================================
echo     UNIQUE HARDWARE - DESKTOP MODE
echo  =========================================
echo.
echo  Launching Electron container...
echo.

:: Run the native:dev composer script
composer native:dev

echo.
pause
