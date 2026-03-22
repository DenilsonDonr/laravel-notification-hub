FROM php:8.5-fpm

# Arguments
ARG user=event-driven-notifications
ARG uid=1000

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    build-essential \
    pkg-config \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensiones PHP para Laravel + PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql
RUN docker-php-ext-install mbstring exif pcntl bcmath zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario no root
RUN useradd -u $uid -ms /bin/bash $user

WORKDIR /var/www

USER $user
