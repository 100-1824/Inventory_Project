@echo off
echo ============================================
echo Setup Local MySQL for Migration Testing
echo ============================================
echo.
echo This script helps you test MySQL configuration locally
echo before deploying to InfinityFree.
echo.
echo ============================================
echo.

echo PREREQUISITES:
echo - XAMPP, WAMP, or similar local MySQL server running
echo - MySQL database created (e.g., 'inventory_test')
echo - MySQL credentials ready
echo.

set /p continue="Do you want to create a MySQL test environment file? (y/n): "
if /i not "%continue%"=="y" (
    echo Cancelled.
    pause
    exit /b 0
)

echo.
echo Please enter your LOCAL MySQL details:
echo.

set /p db_host="MySQL Host (usually 127.0.0.1): "
set /p db_port="MySQL Port (usually 3306): "
set /p db_name="Database Name: "
set /p db_user="Database Username (usually root): "
set /p db_pass="Database Password: "

echo.
echo Creating .env.mysql file...

(
echo APP_NAME="Unique Hardware"
echo APP_ENV=local
echo APP_KEY=base64:6pnbOMcYi51ufmNY5H4ipGgW95N5lsg/XQg8S4CGb6g=
echo APP_DEBUG=true
echo APP_URL=http://localhost
echo.
echo APP_LOCALE=en
echo APP_FALLBACK_LOCALE=en
echo APP_FAKER_LOCALE=en_US
echo.
echo APP_MAINTENANCE_DRIVER=file
echo BCRYPT_ROUNDS=12
echo.
echo LOG_CHANNEL=stack
echo LOG_STACK=single
echo LOG_DEPRECATIONS_CHANNEL=null
echo LOG_LEVEL=debug
echo.
echo # Local MySQL Configuration
echo DB_CONNECTION=mysql
echo DB_HOST=%db_host%
echo DB_PORT=%db_port%
echo DB_DATABASE=%db_name%
echo DB_USERNAME=%db_user%
echo DB_PASSWORD=%db_pass%
echo.
echo SESSION_DRIVER=database
echo SESSION_LIFETIME=120
echo SESSION_ENCRYPT=false
echo SESSION_PATH=/
echo SESSION_DOMAIN=null
echo.
echo BROADCAST_CONNECTION=log
echo FILESYSTEM_DISK=local
echo QUEUE_CONNECTION=sync
echo.
echo CACHE_STORE=database
echo.
echo MAIL_MAILER=log
echo MAIL_HOST=127.0.0.1
echo MAIL_PORT=2525
echo MAIL_USERNAME=null
echo MAIL_PASSWORD=null
echo MAIL_FROM_ADDRESS="hello@example.com"
echo MAIL_FROM_NAME="${APP_NAME}"
echo.
echo VITE_APP_NAME="${APP_NAME}"
) > .env.mysql

echo.
echo ✓ Created .env.mysql file
echo.
echo ============================================
echo Next Steps:
echo ============================================
echo.
echo 1. Backup your current .env file:
echo    copy .env .env.backup
echo.
echo 2. Switch to MySQL configuration:
echo    copy .env.mysql .env
echo.
echo 3. Run migrations:
echo    php artisan migrate:fresh --seed
echo.
echo 4. Test your application with MySQL:
echo    php artisan serve
echo.
echo 5. Once confirmed working, export database:
echo    .\export_mysql_database.bat
echo.
echo 6. To switch back to SQLite:
echo    copy .env.backup .env
echo.
echo ============================================
pause
