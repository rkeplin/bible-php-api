up:
	docker-compose up -d

down:
	docker-compose down

test:
	docker-compose exec php-api sh -c "cd tests && /usr/local/bin/phpunit  --coverage-clover=coverage/coverage.xml"
