# Use the official PHP 8.3 image as a base
FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    wget \
    software-properties-common \
    gnupg \
    zip \
    unzip \
    && apt-get clean

# Install pdftk dependencies and pdftk
RUN apt-get update && apt-get install -y \
    pdftk

# Install composer
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet \
    && mv composer.phar /usr/local/bin/composer
# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /app

COPY . /app

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install -n

# Set the default command to run a shell
CMD ["bash"]
