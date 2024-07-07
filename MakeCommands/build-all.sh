docker compose exec php composer install

docker compose exec php npm install

docker compose exec php npm rebuild node-sass

docker compose exec php php bin/console doctrine:schema:update --complete --dump-sql

docker compose exec php php bin/console doctrine:schema:update --force

docker compose exec php yarn install

docker compose exec php yarn encore dev

docker compose exec php php bin/console doctrine:database:drop --force --if-exists

docker compose exec php php bin/console doctrine:database:create --if-not-exists

docker compose exec php php bin/console doctrine:schema:update --dump-sql --complete

docker compose exec php php bin/console doctrine:schema:update --force --complete
