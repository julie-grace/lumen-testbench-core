#!/bin/bash

cp -rf vendor/laravel/lumen-framework/config/*.php lumen/config/
cp -rf vendor/laravel/lumen/database/migrations/*.php lumen/migrations/
cp -rf vendor/laravel/lumen/resources/lang/en/*.php lumen/resources/lang/en/

awk '{sub(/production/,"testing")}1' lumen/config/app.php > lumen/config/temp.stub && mv lumen/config/temp.stub lumen/config/app.php
awk '{sub(/App\\Providers/,"// App\\Providers")}1' lumen/config/app.php > lumen/config/temp.stub && mv lumen/config/temp.stub lumen/config/app.php
