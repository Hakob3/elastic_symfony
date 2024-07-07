docker compose exec php php bin/console doctrine:database:drop --force --if-exists --env=test

docker compose exec php php bin/console doctrine:database:create --if-not-exists --env=test

docker compose exec php php bin/console doctrine:schema:update --dump-sql --complete --env=test

docker compose exec php php bin/console doctrine:schema:update --force --complete --env=test

docker compose exec php php bin/console patch:execute --env=test
