#!/bin/bash

composer install

# Boucle pour exécuter la migration jusqu'à ce qu'elle se termine avec succès
while true; do
  php bin/console d:m:m --no-interaction && break
  # Attend quelques secondes entre les tentatives
  sleep 5s
done

php bin/console cache:clear
chown -R www-data:www-data /var/www/var

php bin/console lexik:jwt:generate-keypair

# Une fois la migration réussie, démarrez php-fpm
php-fpm