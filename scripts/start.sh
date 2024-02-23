#!/bin/bash

# Start MariaDB server
service mariadb start

# Wait for MariaDB to be fully initialized (retry up to 30 times, waiting 1 second between each attempt)
attempt=0
while [ $attempt -lt 30 ]; do
	if mysqladmin ping &>/dev/null; then
    	echo "MariaDB is up and running."
    	break
	else
    	echo "MariaDB is not yet available. Retrying..."
    	attempt=$((attempt+1))
    	sleep 1
	fi
done

# If MariaDB failed to start within the specified attempts, exit with an error
if [ $attempt -eq 30 ]; then
	echo "Error: MariaDB failed to start within the specified time."
	exit 1
fi

# Set the database name, username, and password
dbname="kinsta_docker_auth"
dbuser="kinsta_user"
dbpassword="your_password_here"

# Create the database if it does not exist
mysql -u root -e "CREATE DATABASE IF NOT EXISTS $dbname;"

# Create a database user and assign privileges to the database
mysql -u root -e "CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpassword';"
mysql -u root -e "GRANT ALL PRIVILEGES ON $dbname.* TO '$dbuser'@'localhost';"
mysql -u root -e "FLUSH PRIVILEGES;"

# Create users table if it does not exist
mysql -u root -e "USE $dbname; CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(20) UNIQUE NOT NULL,
	password_hash VARCHAR(255) NOT NULL
);"

# Start Apache server
apache2ctl -D FOREGROUND
