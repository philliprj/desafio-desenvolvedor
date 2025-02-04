#!/bin/bash

composer install --no-plugins --no-scripts

php artisan key:generate
php artisan migrate

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
