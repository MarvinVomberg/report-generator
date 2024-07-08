# Use the official PHP 8.3 image as a base
FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    wget \
    software-properties-common \
    gnupg \
    && apt-get clean

# Install pdftk dependencies and pdftk
RUN apt-get update && apt-get install -y \
    pdftk

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory
WORKDIR /app

# Copy any application files (if any)
COPY . /app

# Set the default command to run a shell
CMD ["bash"]
