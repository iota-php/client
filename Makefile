# commands
COMPOSER_CMD=composer
PHPCS_CMD=php-cs-fixer
PHPUNIT_CMD=phpunit
PHPSTAN_CMD=phpstan
SECURITY_CHECKER_CMD=security-checker
PHP_CMD=php
# default configuration
TARBALL_OPTIONS=

help:                                                                           ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# targets for the developers
dev-init: composer-install                                                      ## Run all build scripts and import
dev-update: dev-init                                                            ## alias for "dev-init"
dev-check: linters phpunit phpcsfixer phpstan

# dev environment
composer-install:                                                               ## the good old composer install
	$(COMPOSER_CMD) install --no-interaction --prefer-dist --optimize-autoloader

# test targets
linters:                                                                        ## lint symfony app related sources
	find ./src -name "*.php" -print0 | xargs -0 -n1 -P8 php -l

phpunit:                                                                        ## run phpunit tests
	$(PHPUNIT_CMD) $(OPTIONS)

security-checker:                                                               ## check composer dependencies for vulnerabilities
	$(SECURITY_CHECKER_CMD) security:check composer.lock

# Cody analysis targets
phpcsfixer:                                                                     ## run php code style checker
	$(PHPCS_CMD) fix --diff --dry-run $(OPTIONS)

phpstan:
	-$(PHPSTAN_CMD) analyse -l 5 --autoload-file=vendor/autoload.php src

# report targets
phpunit-report: reports                                                         ## run phpunit and create reports
	$(MAKE) OPTIONS='--coverage-html=reports/phpunit-html-coverage --log-junit=reports/phpunit.junit.xml --coverage-clover=reports/phpunit.clover.xml' phpunit

phpcs-report: reports                                                           ## run php code style checker and create report
	$(MAKE) OPTIONS='--report=checkstyle --report-file=reports/phpcs.cs.xml' phpcs

reports:                                                                        ## create reports directory
	mkdir -p reports

# deployment targets
tarball:                                                                        ## create deployable tarball
	touch iota-php.tar.gz
	tar -czf iota-php.tar.gz . --exclude ./reports --exclude iota-php.tar.gz --exclude vendor --exclude examples --exclude vars $(TARBALL_OPTIONS)

.PHONY: help
.PHONY: composer-install
.PHONY: linters phpunit security-checker phpcsfixer tarball
