@echo off
echo ============================================
echo Building Production Assets for InfinityFree
echo ============================================
echo.

REM Set up PHP path (using bundled PHP from NativePHP)
set PHP_PATH=bin\php\php.exe
if not exist "%PHP_PATH%" (
    echo Looking for system PHP...
    where php >nul 2>&1
    if errorlevel 1 (
        echo.
        echo WARNING: PHP not found in system PATH.
        echo This is OK - we'll skip optimization steps.
        set PHP_PATH=
    ) else (
        set PHP_PATH=php
    )
) else (
    echo Found bundled PHP: %PHP_PATH%
)
echo.

REM Check if vendor folder exists (dependencies already installed)
if not exist "vendor" (
    echo [Step 1/4] Composer dependencies not found!
    echo.
    echo You need to install Composer dependencies first.
    echo Please run one of these commands:
    echo    * composer install
    echo    * If you have composer installed elsewhere
    echo.
    echo Or copy the vendor folder from your desktop app build.
    pause
    exit /b 1
) else (
    echo [Step 1/4] Composer dependencies found (vendor folder exists^) SKIPPED
    echo Note: For production, dependencies are already bundled.
)
echo.

REM Install NPM Dependencies
echo [Step 2/4] Installing NPM dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: NPM install failed!
    echo Make sure Node.js is installed.
    pause
    exit /b 1
)
echo.

REM Build Frontend Assets with Vite
echo [Step 3/4] Building frontend assets with Vite...
call npm run build
if errorlevel 1 (
    echo ERROR: Vite build failed!
    pause
    exit /b 1
)
echo.

REM Optimize Configuration (optional - only if PHP available)
echo [Step 4/4] Laravel optimization...
if "%PHP_PATH%"=="" (
    echo SKIPPED: PHP not available for optimization
    echo This is OK - Laravel will work without pre-caching.
) else (
    echo Running Laravel cache optimization...
    call %PHP_PATH% artisan config:cache
    call %PHP_PATH% artisan route:cache
    call %PHP_PATH% artisan view:cache
    echo Optimization complete!
)
echo.

echo ============================================
echo Build Complete!
echo ============================================
echo.
echo PREPARED FOR DEPLOYMENT:
echo [*] Frontend assets built
echo [*] Dependencies ready
echo.
echo ============================================
echo FILES TO UPLOAD TO INFINITYFREE:
echo ============================================
echo.
echo Upload to /htdocs/ (Laravel root):
echo    *  /app folder
echo    * /bootstrap folder
echo    * /config folder
echo    * /database folder
echo    * /resources folder
echo    * /routes folder
echo    * /storage folder (set permissions to 755)
echo    * /vendor folder
echo    * artisan file
echo    * composer.json
echo    * .env file (create from infinityfree.env template)
echo.
echo Upload to /htdocs/public_html/ (public folder):
echo    * /build folder (compiled assets)
echo    * .htaccess file
echo    * index.php file
echo    * favicon.ico, robots.txt
echo.
echo ============================================
echo NEXT STEPS:
echo ============================================
echo 1. Review deployment_guide.md for detailed instructions
echo 2. Set up your InfinityFree account and database
echo 3. Upload files via FTP (see above)
echo 4. Configure .env file with your database credentials
echo 5. Import database using phpMyAdmin
echo.
pause
