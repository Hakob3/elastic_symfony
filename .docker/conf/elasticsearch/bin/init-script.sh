##!/bin/bash
SERVER="localhost"
PORT="9200"
DELAY=5  # Задержка между попытками в секундах
FLAG_FILE="/usr/share/elasticsearch/data/password_set.flag"

# Функция для проверки доступности Elasticsearch
wait_for_elasticsearch() {
  until nc -z -w5 $SERVER $PORT; do
    echo "Waiting for Elasticsearch to be available..."
    sleep $DELAY
  done
  echo "Elasticsearch is available!"
}

# Запуск Elasticsearch в фоне
echo "Running elasticsearch"
/usr/local/bin/docker-entrypoint.sh elasticsearch &

# Ожидание доступности Elasticsearch
wait_for_elasticsearch

# Проверка наличия флага и выполнение команды при его отсутствии
if [ ! -f "$FLAG_FILE" ]; then
  echo "Running additional command to set kibana password"
  HTTP_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -u elastic:$ELASTIC_PASSWORD -X POST "http://$SERVER:$PORT/_security/user/kibana_system/_password" -H "Content-Type: application/json" -d '{"password":"'$KIBANA_PASSWORD'"}')

  if [ "$HTTP_RESPONSE" -eq 200 ]; then
    echo "Password set successfully. Creating flag file."
    touch "$FLAG_FILE"
  else
    echo "Failed to set password. Response: $HTTP_RESPONSE"
  fi
else
  echo "Password already set. Skipping the password setup command."
fi
wait