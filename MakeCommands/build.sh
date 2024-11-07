cp .env.example ".env"

docker compose build --no-cache

docker compose up -d

docker compose exec php composer install

docker compose exec php php bin/console doctrine:database:drop --force --if-exists

docker compose exec php php bin/console doctrine:database:create --if-not-exists

docker compose exec php php bin/console doctrine:schema:update --dump-sql

docker compose exec php php bin/console doctrine:schema:update --force

docker compose exec php php bin/console doctrine:fixtures:load --env=dev -n

echo "http://localhost"

