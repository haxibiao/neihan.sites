#!/bin/bash

php artisan migrate

php artisan fix:data movies

php artisan movie:sync

