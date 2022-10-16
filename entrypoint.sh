#!/bin/sh

cd /var/www/grs-backend || exit
php artisan down
php artisan optimize
php artisan migrate:fresh --seed
php artisan storage:link
php artisan up
echo 'Deployment finished.'
php-fpm
