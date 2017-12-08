up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

bash:up
	docker-compose exec php bash

test:up
	docker-compose exec php bash -c './vendor/bin/phpunit'

test-file:up
	docker-compose exec php bash -c './vendor/bin/phpunit $(FILE)'

clean:up
	wget -N http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer.phar
	docker-compose exec php bash -c 'php ./.docker/php-cs-fixer.phar fix ./src --rules=@PSR2'
	docker-compose exec php bash -c 'php ./.docker/php-cs-fixer.phar fix ./examples/kitchen_sink --rules=@PSR2'
	docker-compose exec php bash -c 'rm ./.php_cs.cache'

examples:up
	docker-compose exec php bash -c 'node node-keccak/keccak384-srv.js & (cd ./examples && php -S 0.0.0.0:8080)'
