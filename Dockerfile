# Use the official PHP image as the base image
FROM php:8.0-cli-alpine

# Install required packages
RUN apk update && apk add --no-cache curl git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy the project files to the working directory
COPY . /app

# Install the project dependencies
RUN composer install

# Run the tests
CMD ["./vendor/bin/pest"]
