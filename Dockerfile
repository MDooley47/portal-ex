FROM php:7.0-apache

# Install dependencies from apt
RUN apt-get update \
    && apt-get install -y \
 		git \
        wget \
 		zlib1g-dev \
 		libgmp-dev \
        libicu-dev \
        libpq-dev

# Install cli client for PostgreSQL
# psql -h HOST DBNAME UNAME
# RUN apt-get install -y postgresql-client

# Install php extensions from docker-php-ext-install
RUN docker-php-ext-install zip \
    && docker-php-ext-install gmp \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql

# Changes apache public directory to /var/www/public and removes /var/www/html
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
    && rm -rf /var/www/html

# Change working directory to /var/www
WORKDIR /var/www/

# Copy code into container
COPY . /var/www/

# Install composer, run it, and remove it
RUN chmod +x ./composerInstallDependencies.sh \
    && ./composerInstallDependencies.sh \
    && rm ./composerInstallDependencies.sh
