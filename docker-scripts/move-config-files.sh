#!/usr/bin/env bash

basePath="/var/www"

mv ${basePath}/docker-scripts/data/*.conf /etc/apache2/conf-enabled/.
mv ${basePath}/docker-scripts/data/*.ini /usr/local/etc/php/conf.d/.

# Checks if the .env exists. If not use .env.example
if [ ! -f /var/www/.env ]
then
    echo -e "\e[31m.env does not exist. Generating one from .env.example.\e[39m"
    cp ${basePath}/.env.example ${basePath}/.env
fi
