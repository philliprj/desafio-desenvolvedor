#!/bin/bash

composer install --no-plugins --no-scripts

php artisan key:generate
php artisan migrate
