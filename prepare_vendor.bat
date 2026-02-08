@echo off
setlocal
echo ============================================
echo Preparing Vendor Folder for InfinityFree
echo ============================================

REM Add bundled PHP to PATH
set "PROJECT_ROOT=%~dp0"
set "PHP_PATH=%PROJECT_ROOT%bin\php"
if exist "%PHP_PATH%\php.exe" (
    echo Found bundled PHP at %PHP_PATH%
    set "PATH=%PHP_PATH%;%PATH%"
) else (
    echo Bundled PHP not found, relying on system PATH...
)

echo [1/2] Updating Composer dependencies (PHP 8.3 compatible, No Dev)...
echo Running: composer update --no-dev --optimize-autoloader

call composer update --no-dev --optimize-autoloader
if errorlevel 1 (
    echo.
    echo ERROR: Composer update failed!
    echo.
    echo It seems 'composer' is not in your PATH or cannot run with the current PHP.
    echo Please run this command manually in your terminal if you have Composer installed:
    echo composer update --no-dev --optimize-autoloader
    echo.
    pause
    exit /b 1
)

echo.
echo [2/2] Creating vendor.zip...
if exist vendor.zip del vendor.zip
powershell -Command "Compress-Archive -Path vendor -DestinationPath vendor.zip -Force"
if errorlevel 1 (
    echo ERROR: Zipping failed!
    pause
    exit /b 1
)

echo.
echo SUCCESS! vendor.zip is ready.
echo Location: %CD%\vendor.zip
pause
