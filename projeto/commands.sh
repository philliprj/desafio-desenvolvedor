#!/bin/bash

chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

composer install --no-plugins --no-scripts

php artisan key:generate
php artisan migrate

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
