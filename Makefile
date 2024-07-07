.PHONY: $(MAKECMDGOALS)

.DEFAULT_GOAL := help
SHELL := /bin/bash

up: #Запуск наших контейнеров
	sh MakeCommands/up.sh

up-alone: #Запуск наших контейнеров, остановка других контейнеров, которые остались открытыми
	sh MakeCommands/up-alone.sh

down: #Остановка контейнера
	sh MakeCommands/down.sh

down-all: #Остановка наших контейнеров + всех остальных, которые остались открытыми
	sh MakeCommands/down-all.sh

##Команды для работы с Symfony + Doctrine

dictionary-dump: #Выгрузка словарей для переводчика из базы в файл
	sh MakeCommands/dictionary-dump.sh

fixtures: #Заполнение базы данных таблицами из сущностей + загрузка фикстур
	sh MakeCommands/fixtures.sh

fixtures-test: #Заполнение тестовой базы данных таблицами из сущностей + загрузка фикстур
	sh MakeCommands/fixtures-test.sh

db-update: #Заполнение базы данных таблицами из сущностей
	sh MakeCommands/db-update.sh

db-validate: #Валидация БД
	sh MakeCommands/db-validate.sh

composer-install: #Запуск установки пакетов композером
	sh MakeCommands/composer-install.sh

npm-install: #Запуск установки NPM пакетов
	sh MakeCommands/npm-install.sh

yarn: #Запуск сборка проекта при помощи yarn
	sh MakeCommands/yarn.sh

build: #Собрать проект(composer install + npm install + yarn encore dev + dump sql + update db)
	sh MakeCommands/build.sh

build-all: #Собрать проект(composer install + npm install + yarn encore dev + dump sql + update db)
	sh MakeCommands/build-all.sh

jwt-key-gen: #Генерируем ключи для JWT авторизации
	sh MakeCommands/jwt-key-gen.sh

host: #Добавить хосты проекта в файл hosts
	sh MakeCommands/host.sh

rm: #Добавить хосты проекта в файл hosts
	sh MakeCommands/rm.sh

test-all: #Запуск всех тестов
	sh MakeCommands/test-all.sh

lng: #dump translator dictionary
	sh MakeCommands/lng.sh

import: #import data from iiko
	sh MakeCommands/import.sh
