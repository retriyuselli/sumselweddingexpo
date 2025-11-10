php artisan shield:generate --all
php artisan migrate --path=database/migrations/
cd /home/u380354370/domains/sumselweddingexpo.com/public_html

# Server Deployment Commands (Run setelah git pull)

git pull origin main

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan optimize
