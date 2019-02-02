build:
	docker-compose build

clean:
	docker-compose stop
	docker-compose kill
	docker-compose down -v

shell:
	docker-compose run --rm app bash
