# Description

Please create a simple Laravel/Symfony/(Any other framework you like) site where a user will be able to provide his city and country via form and after submission system will display current weather forecast.

Forecast temperature should be calculated as an average based on different APIs, at least 2 different data sources (ex. API1 will return temperature 25, API2 will return temperature 27 so the result should be 25+27/2 ie. 26)
Feel free to use https://openweathermap.org/API and any other API you like.
Few notes:
- please implement proper error handling
- results should be stored in the database
- a simple caching mechanism should be added
- ability to easily add new data sources (how to register them, interfaces etc.)
- clean data separation
- nice to have - latest PHP mechanisms (ex. traits)

Tested on Linux (Ubuntu):

1. Docker version 20.10.3, build 48d30b5
1. docker-compose version 1.28.4, build cabd5cfb

# Usage

Clone repository, `cd` inside, create `docker-compose.yml` based on `docker-compose.yml.dist` with `cp docker-compose.yml.dist docker-compose.yml` command. If needed, change configuration according to the comments inside. Then define your `APP_SECRET` in the correct file based on your ENV (`docker/php/.env.app.[dev/prod]`) and run afterwards:

<pre>
docker-compose build
docker-compose up
</pre>

After that log into container with `docker exec -it app.php bash`, where `app.php` is the default container name from `docker-compose.yml.dist`. Then run:

<pre>
composer install
</pre>

From this point forward, application should be available under `http://localhost:8050/`.

# Overview

All PHP extensions can be installed via `docker-php-ext-install` command in docker/php/Dockerfile. Examples and usage:
`https://gist.github.com/giansalex/2776a4206666d940d014792ab4700d80`.

## PhpStorm configuration

Open directory including cloned repository as directory in PhpStorm.

### Interpreter

1. `Settings` -> `Languages & Frameworks` -> `PHP` -> `Servers`: create server with name `docker` (the same as in ENV variable `PHP_IDE_CONFIG`), host `localhost`, port `8050` (default from docker-compose.yml.dist).
1. Tick `Use path mappings` -> set `File/Directory` <-> `Absolute path on the server` as: `</absolute/path>/symfony-docker/app` <-> `/var/www/app` (default from docker-compose.yml.dist).
1. `Settings` -> `Languages & Frameworks` -> `PHP`: three dots next to the field `CLI interpreter` -> `+` button -> `From Docker, Vagrant(...)` -> from `docker-compose`, from service `php`, server `Docker`, configuration files `./docker-compose`. After creating in `Lifecycle` section ensure to pick `Always start a new container (...)`, in `General` refresh interpreter data.

### xdebug

1. `Settings` -> `Languages & Frameworks` -> `PHP` -> `Debug: Xdebug` -> `Debug port`: `9003` (set by default) and check `Can accept external connections`.
1. Click `Start Listening for PHP Debug connections` -> `+` button, set breakpoints and refresh website.

### PHPCS

1. Copy `app/phpcs.xml.dist` and name it `phpcs.xml`. Tweak it to your needs.
1. `Settings` -> `Languages & Frameworks` -> `PHP` -> `Quality Tools` -> `PHP_CodeSniffer` -> `Configuration`: three dots, add interpreter with `+` and validate paths. By default, there should be correct path mappings and paths already set to `/var/www/app/vendor/bin/phpcs` and `/var/www/app/vendor/bin/phpcbf`.
1. `Settings` -> `Editor` -> `Inspections` -> `PHP` -> `Quality tools` -> tick `PHP_CodeSniffer validation` -> tick `Show sniff name` -> set coding standard to `Custom` -> three dots and type `/var/www/app/phpcs.xml` (path in container).

### MySQL

Open `Database` section on the right bar of IDE -> `Data Source` -> `MySQL` -> set host to `localhost`, port to `33306`, user to `app_user`, pass `app_pass` (defaults from docker-compose.yml.dist).

### PHPUnit

1. Copy `phpunit.xml.dist` into `phpunit.xml`.
1. Login into `mint_2.php` container and run `./bin/phpunit`.
1. `Settings` -> `Languages & Frameworks` -> `PHP` -> `Test frameworks`. Click `+` and `PHPUnit by Remote Intepreter` -> pick interpreter. In `PHPUnit library` tick `Path to phpunit.phar` and type `bin/phpunit`. Click refresh icon. In `Test runner` section set `Default configuration file` to `phpunit.xml` and `Default bootstrap file` to `tests/bootstrap.php`.

# Disclaimer

Although there are present different files for `prod` and `dev` environments these are only stubs and this repo is not suitable to run on `prod` environment. The idea was to create as much integral, self-contained and flexible environment for `development` as possible and these files are here merely to easily mimic `prod` env and point out differences in configuration.
