<h3>docker commands : </h3>

<h5>phpstan</h5>
- docker-compose exec php php vendor/bin/phpstan

<h5>doctrine drop the database</h5>
- docker-compose exec php php bin/console doctrine:database:drop --force

<h5>doctrine recreate the DB</h5>
- docker-compose exec php php bin/console doctrine:database:create

<h5>doctrine run the migrations</h5>
- docker-compose exec php php bin/console doctrine:migrations:migrate

<h5>doctrine run the database fixtures</h5>
- docker-compose exec php php bin/console doctrine:fixtures:load