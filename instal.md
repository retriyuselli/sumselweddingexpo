php artisan shield:generate --all
php artisan migrate --path=database/migrations/
cd /home/u380354370/domains/sumselweddingexpo.com/public_html

# Jalankan hanya UserSeeder

php artisan db:seed --class=DatabaseSeeder

php artisan db:seed --class=UserSeeder

# Jalankan hanya BlogSeeder

php artisan db:seed --class=BlogSeeder

# Jalankan hanya VendorSeeder

php artisan db:seed --class=VendorSeeder

# Server Deployment Commands (Run setelah git pull)

git pull origin main

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan optimize
