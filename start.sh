#!/bin/bash

cp .env.example .env

sed -i 's/DB_HOST=.*/DB_HOST=mysql/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=distance_learning/' .env
sed -i 's/DB_USERNAME=.*/DB_USERNAME=root/' .env
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=root/' .env

docker-compose up -d

docker-compose exec api_distance_learning composer install

docker-compose exec api_distance_learning php artisan key:generate

docker-compose exec api_distance_learning php artisan migrate

docker-compose exec api_distance_learning php artisan db:seed

echo "Projeto inicializado com sucesso!"
