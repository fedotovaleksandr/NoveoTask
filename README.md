NoveoTask
=========

##Docker deploy 
https://github.com/fedotovaleksandr/NoveoTask/blob/master/docker/README.md

- build containers ```bash docker-compose build --force-rm --no-cache```
- create db ```bash docker-compose  exec php php /var/www/symfony/bin/console doctrine:database:create```
- create/update schema ```bash docker-compose  exec php php /var/www/symfony/bin/console doctrine:schema:update ```
- load fixtures ```bash docker-compose  exec php php /var/www/symfony/bin/console doctrine:fixture:load  ```
- clear cache```bash docker-compose  exec php php /var/www/symfony/bin/console cache:clear```
- run tests ```bash docker-compose  exec php bash /var/www/symfony/vendor/bin/phpunit```

##Quick Start
- create database ```doctrine:schema:create``` 
- load fixture to dev database  ```doctrine:fixtures:load  ```
- and run buildin server ```server:run```


##Tests
- !Before Test load fixtures
- ```vendor/bin/phpunit```

##TODO

I do not have time to do some important feaures: 
- Write custom error response for codes :404,405,500 response code (e.g RouteNotFound , InvalidHttpMethos etc.) )
- Generate apidocs with [NelmioApiDocBundle](http://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html)
- Write more test ,for example validate clear caching after my custom events
- Also test for check form constrains

##Small Description of api methods
####GET /api/v1/users/fetch 
fetch all users
####POST /api/v1/users/create 
create new user
format: ```json {"email":"test@mail.test",
                 "firstName":"tester",
                 "lastName":"testovich",
                 "state":false,
                 "creationDate":"2017-01-29T19:45:10+00:00",
                 "groups":[91,92]}```

####GET /api/v1/users/{id}/ 
user info
####PATCH /api/v1/users/{id}/ 
update user
format: ```json {"email":"test@mail.test",
                 "firstName":"tester",
                 "lastName":"testovich",
                 "state":false,
                 "creationDate":"2017-01-29T19:45:10+00:00",
                 "groups":[91,92]}```

####GET /api/v1/groups/fetch 
fetch all groups
####POST /api/v1/groups/create 
create group 
format: ```json {"name":"test","users":[604,605]}```
####GET /api/v1/groups/{id}/ 
info group
####PATCH /api/v1/groups/{id}/ 
update group 
format: ```json {"name":"test","users":[604,605]}```
