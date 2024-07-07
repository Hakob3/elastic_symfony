docker compose exec php php bin/console doctrine:schema:update --dump-sql --complete

docker compose exec php php bin/console doctrine:schema:update --force --complete
