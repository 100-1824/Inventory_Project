@echo off
echo ============================================
echo Creating Zip Files for InfinityFree Upload
echo ============================================
echo.
echo This will create zip files for easier upload to InfinityFree.
echo Large folders like 'vendor' upload better as zip files.
echo.

REM Check if vendor folder exists
if not exist "vendor" (
    echo ERROR: vendor folder not found!
    pause
    exit /b 1
)

echo Creating vendor.zip...
powershell -command "Compress-Archive -Path 'vendor' -DestinationPath 'vendor.zip' -Force"
if errorlevel 1 (
    echo ERROR: Failed to create vendor.zip
    pause
    exit /b 1
)
echo Created vendor.zip successfully!
echo.

echo ============================================
echo Zip files created successfully!
echo ============================================
echo.
echo NEXT STEPS:
echo 1. In InfinityFree File Manager, delete the partial vendor folder
echo 2. Click Upload button
echo 3. Select "Zip & Extract" tab
echo 4. Upload vendor.zip
echo 5. The file manager will auto-extract it
echo.
echo File created:
echo - vendor.zip (in current folder)
echo.
pause
