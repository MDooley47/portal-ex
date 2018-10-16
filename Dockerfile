FROM php:7.1-apache

# Install dependencies from apt
RUN mkdir -p /usr/share/man/man1 /usr/share/man/man7
RUN apt-get update
# Allows postgresql-client to install without issues
RUN mkdir -p /usr/share/man/man1 \
    && mkdir -p /usr/share/man/man7

RUN apt-get install -y \
    git \
    wget \
    zlib1g-dev \
    libgmp-dev \
    libicu-dev \
    libpq-dev \
    apache2-dev \
    postgresql-client

# Install php extensions from docker-php-ext-install
RUN docker-php-ext-install zip \
    && docker-php-ext-install gmp \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# install mod_xsendfile
RUN wget -O /tmp/mod_xsendfile.tar.gz https://tn123.org/mod_xsendfile/mod_xsendfile-0.12.tar.gz \
    && mkdir /tmp/mod_xsendfile \
    && tar -xf /tmp/mod_xsendfile.tar.gz -C /tmp/mod_xsendfile --strip-components=1 \
    && cd /tmp/mod_xsendfile \
    && apxs2 -cia mod_xsendfile.c \
    && rm -r /tmp/*

# Apache config and certificates
COPY docker-scripts/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker-scripts/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY docker-scripts/apache/nebraskacloud-org.crt /etc/ssl/certs/nebraskacloud-org.crt
COPY docker-scripts/apache/nebraskacloud-org.key /etc/ssl/private/nebraskacloud-org.key
COPY docker-scripts/apache/godaddy.crt /etc/ssl/certs/godaddy.crt

# Changes apache public directory to /var/www/public and removes /var/www/html
RUN a2enmod rewrite \
    && a2enmod ssl \
    && rm -rf /var/www/html \
    && ln -s /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-enabled/default-ssl.conf

# Change working directory to /var/www
WORKDIR /var/www/

# Copy code into container
COPY . /var/www/

# Arguments for changing environmental variables
ARG app_name
ARG db_host
ARG db_name
ARG db_username
ARG db_password
ARG setup_database
ARG setup_permissions
ARG storage_path

# Run docker-scripts
    # make them executable
        RUN chmod +x /var/www/docker-scripts/*.sh
    # Install composer, run it, and remove it
        RUN ./docker-scripts/composerInstallDependencies.sh
    # Install move config files
        RUN ./docker-scripts/move-config-files.sh
    # Updates ./.env
        RUN ./docker-scripts/update-env.sh \
            app_name "$app_name" \
            db_host "$db_host" \
            db_name "$db_name" \
            db_username "$db_username" \
            db_password "$db_password" \
            setup_database "$setup_database" \
            setup_permissions "$setup_permissions" \
            storage_path "$storage_path"
    # Fix permissions // Should only have run once. WILL NOT RUN WITH DOCKER-COMPOSE
    #    RUN ./docker-scripts/permissionFixing.sh
