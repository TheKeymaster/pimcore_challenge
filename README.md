# Pimcore Coding Challenge

## Synopsis

This repository was created as a "Coding-Challenge" to get familiar with Pimcore. The application creates a route
that lists football-teams. Each team has players, a logo and many more data-points. Once the installation is
complete you can see it for yourself at [http://localhost:8012/football/teams](http://localhost:8012/football/teams).

## Installation

### Prerequisites

* PHP
* Composer
* Docker
* Docker-Compose

### Install

1. Start the containers with Docker-Compose
    ```bash
    docker-compose up --detatch
    ```

2. Install necessary dependencies and enter app & mysql credentials
    ```
    docker exec -it <container-name> bash # Shell into container
    ./vendor/bin/pimcore-install --mysql-host-socket=db # Name of the MySQL docker container
    ```

3. Access Web Interface
    Go to `https://localhost:8012/admin` with the credentials you've entered the previous step.



asdasd

This skeleton should be used by experienced Pimcore developers for starting a new project from the ground up. 
If you are new to Pimcore, it's better to start with our demo package, listed below 😉

## Getting started
```bash
COMPOSER_MEMORY_LIMIT=-1 composer create-project pimcore/skeleton my-project
cd ./my-project
./vendor/bin/pimcore-install
```

- Point your virtual host to `my-project/public` 
- Open https://your-host/admin in your browser
- Done! 😎

## Docker

You can also use Docker to set up a new Pimcore Installation.
You don't need to have a PHP environment with composer installed.

### Prerequisites

* Your user must be allowed to run docker commands (directly or via sudo).
* You must have docker compose installed.
* Your user must be allowed to change file permissions (directly or via sudo).

### Follow these steps
1. Initialize the skeleton project using the `pimcore/pimcore` image
``docker run -u `id -u`:`id -g` --rm -v `pwd`:/var/www/html pimcore/pimcore:php8.2-latest composer create-project pimcore/skeleton my-project``

2. Go to your new project
`cd my-project/`

3. Part of the new project is a docker compose file
    * Run `sed -i "s|#user: '1000:1000'|user: '$(id -u):$(id -g)'|g" docker-compose.yaml` to set the correct user id and group id.
    * Start the needed services with `docker compose up -d`

4. Install pimcore and initialize the DB
    `docker compose exec php vendor/bin/pimcore-install`
    * When asked for admin user and password: Choose freely
    * This can take a while, up to 20 minutes
    * If you select to install the SimpleBackendSearchBundle please make sure to add the `pimcore_search_backend_message` to your `.docker/supervisord.conf` file.

5. Run codeception tests:
   * `docker compose run --user=root --rm test-php chown -R $(id -u):$(id -g) var/ public/var/`
   * `docker compose run --rm test-php vendor/bin/pimcore-install -n`
   * `docker compose run --rm test-php vendor/bin/codecept run -vv`

6. :heavy_check_mark: DONE - You can now visit your pimcore instance:
    * The frontend: <http://localhost>
    * The admin interface, using the credentials you have chosen above:
      <http://localhost/admin>

## Other demo/skeleton packages
- [Pimcore Basic Demo](https://github.com/pimcore/demo)
