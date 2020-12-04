SHELL := /bin/bash

.PHONY: cc phpstan fix fix-src fix-test

cc:
	./bin/console c:cl

phpstan:
	./vendor/bin/phpstan analyse src -l 5

fix: fix-src fix-test

fix-src:
	./vendor/bin/php-cs-fixer fix src

fix-test:
	./vendor/bin/php-cs-fixer fix tests
