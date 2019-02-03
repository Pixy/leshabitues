build:
	docker-compose build

clean:
	docker-compose stop
	docker-compose kill
	docker-compose down -v

init: build
	docker-compose up -d app

shell:
	docker-compose run --rm app bash

migrate:
	docker-compose run --rm app php artisan migrate

tests:
	docker-compose run --rm app php vendor/bin/phpunit

import:
	docker-compose run --rm app php artisan shops:import-list