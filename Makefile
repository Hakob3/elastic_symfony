.PHONY: $(MAKECMDGOALS)

.DEFAULT_GOAL := help
SHELL := /bin/bash

up: #Запуск наших контейнеров
	sh MakeCommands/up.sh

down: #Остановка контейнеров
	sh MakeCommands/down.sh

fixtures: #Заполнение базы данных таблицами из сущностей + загрузка фикстур
	sh MakeCommands/fixtures.sh

db-update: #Заполнение базы данных таблицами из сущностей
	sh MakeCommands/db-update.sh

composer-install: #Запуск установки пакетов композером
	sh MakeCommands/composer-install.sh

build: #Собрать проект и запустить контейнеры
	sh MakeCommands/build.sh

host: #Добавить хосты проекта в файл hosts
	sh MakeCommands/host.sh

