FROM php:7.0-apache

# Install dependencies from apt
RUN apt-get update \
    && apt-get install -y \
 		git \
        wget \
        postgresql-client \
 		zlib1g-dev \
 		libgmp-dev \
        libicu-dev \
        libpq-dev \
        apache2-dev

# Install php extensions from docker-php-ext-install
RUN docker-php-ext-install zip \
    && docker-php-ext-install gmp \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql

# install mod_xsendfile
RUN wget -O /tmp/mod_xsendfile.tar.gz https://tn123.org/mod_xsendfile/mod_xsendfile-0.12.tar.gz \
    && mkdir /tmp/mod_xsendfile \
    && tar -xf /tmp/mod_xsendfile.tar.gz -C /tmp/mod_xsendfile --strip-components=1 \
    && cd /tmp/mod_xsendfile \
    && apxs2 -cia mod_xsendfile.c \
    && rm -r /tmp/*

# Changes apache public directory to /var/www/public and removes /var/www/html
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
    && rm -rf /var/www/html

# Change working directory to /var/www
WORKDIR /var/www/

# Copy code into container
COPY . /var/www/

# Run docker-scripts
RUN chmod +x /var/www/docker-scripts/*.sh   # make them executable
    RUN ./docker-scripts/composerInstallDependencies.sh  # Install composer, run it, and remove it
    RUN ./docker-scripts/move-confs.sh   # Install composer  run it, and remove it
