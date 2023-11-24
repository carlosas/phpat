#!/bin/bash

php_version=$(php -v | head -n 1 | awk '{print $2}')

# Disable on PHP 8.3 until PHP-CS-Fixer supports it
if [[ "$php_version" == "8.3"* ]]; then
    exit 0
else
    vendor/bin/php-cs-fixer check --config ./ci/php-cs-fixer.php
fi
