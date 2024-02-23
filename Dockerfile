FROM php:8.3-apache
EXPOSE 8080
# Install MySQL client, server, and other dependencies
RUN apt-get update && \
	apt-get install -y \
	default-mysql-client \
	default-mysql-server \
	git \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

# Install mysqli PHP extension for MySQL support
RUN docker-php-ext-install mysqli

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set up Apache virtual host
COPY apache-conf/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Set up Apache ports
COPY apache-conf/apache-ports.conf /etc/apache2/ports.conf


# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set environment variables
ENV DB_HOST=localhost \
	DB_USER=kinsta_user \
	DB_PASSWORD=your_password_here \
	DB_NAME=kinsta_docker_auth \
	# Path to the Unix socket file used for connecting to the MariaDB server.
	DB_SOCKET=/var/run/mysqld/mysqld.sock

# Copy PHP application files into the image
COPY . .

# Copy the startup script
COPY scripts/start.sh /usr/local/bin/start.sh

# Make the script executable
RUN chmod +x /usr/local/bin/start.sh

# Execute the startup script
CMD ["/usr/local/bin/start.sh"]

