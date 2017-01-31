docker-compose down
docker-compose build --force-rm --no-cache
docker-compose up &
docker-compose  exec --user root php chown -R :www-data /var/www/symfony
docker-compose  exec --user root chmod -R g+rwx /var/www/symfony
docker-compose  exec --user root chown -R www-data:www-data /var/www/symfony/var
docker-compose  exec php composer install
docker-compose  exec php php /var/www/symfony/bin/console doctrine:database:create
docker-compose  exec php php /var/www/symfony/bin/console doctrine:schema:update
docker-compose  exec php php /var/www/symfony/bin/console doctrine:fixture:load
docker-compose  exec php php /var/www/symfony/bin/console cache:clear
docker-compose  exec php /var/www/symfony/vendor/bin/phpunit
