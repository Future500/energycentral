#!/usr/bin/env bash
export DEBIAN_FRONTEND=noninteractive

# Install dependencies
apt-get -y update
apt-get -y install nginx mysql-server mysql-client phpmyadmin php5-fpm php5-cli php5-gd php5-mcrypt php5-mysql

# Set MySQL root password
mysqladmin -u root password U7KOekqJOMvf3Uu

# Create and import database
mysqladmin -u root -p"U7KOekqJOMvf3Uu" CREATE future500
mysql -u root -p"U7KOekqJOMvf3Uu" future500 < "/vagrant/provisioning/db.sql"

# Configure and restart nginx
sudo -s cp /vagrant/provisioning/nginx.conf /etc/nginx/sites-available/default
sudo -s /etc/init.d/nginx reload
sudo -s /etc/init.d/nginx restart