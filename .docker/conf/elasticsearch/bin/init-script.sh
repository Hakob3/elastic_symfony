##!/bin/bash
#
echo "Starting init-script.sh"
#
## Пример команды инициализации Elasticsearch
#curl -u elastic:$ELASTIC_PASSWORD -X POST "http://localhost:9200/_security/user/kibana_system/_password" -H "Content-Type: application/json" -d '{"password":"'$KIBANA_PASSWORD'"}'
#
echo "Finished setting password for kibana_system"
#
## Запуск Elasticsearch
##exec /usr/local/bin/docker-entrypoint.sh elasticsearch

