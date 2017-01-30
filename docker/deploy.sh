docker-compose down
docker-compose build --force-rm --no-cache
docker-compose up &
docker-compose  exec php composer install
docker-compose  exec php php /var/www/symfony/bin/console doctrine:database:create
docker-compose  exec php php /var/www/symfony/bin/console doctrine:schema:update
docker-compose  exec php php /var/www/symfony/bin/console doctrine:fixture:load
docker-compose  exec php php /var/www/symfony/bin/console cache:clear
docker-compose  exec php /var/www/symfony/vendor/phpunit