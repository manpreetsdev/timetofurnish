docker compose up -d --build
docker exec -it timetofurnish_app sh
docker compose exec app composer install --no-interaction
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force

docker exec timetofurnish_app php artisan migrate --path=database/migrations/2026_05_31_000002_create_offer_product_table.php

docker exec -it timetofurnish_app bash
root@0f45458fd582:/var/www/html# chmod -R 775 storage bootstrap/cachephp artisan optimize:clear

i am facing the issue while uplaodiy mutliple fiel s
https://timetofurnish.com/aiz-uploader/upload
Request Method
POST
Status Code
400 Bad Request
Remote Address
[2606:4700:3030::6815:692]:443
Referrer Policy
strict-origin-when-cross-origin
from admin or seller admin

cd /var/www/vhosts/timetofurnish.com/staging
git pull origin main
which composer
/usr/bin/composer
/opt/plesk/php/8.4/bin/php /usr/bin/composer install --no-dev --optimize-autoloader
