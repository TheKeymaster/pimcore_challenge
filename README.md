# Pimcore Coding Challenge

## Synopsis

This repository was created as a "Coding-Challenge" to get familiar with Pimcore. The application creates a route
that lists football-teams. Each team has players, a logo and many more data-points. Once the installation is
complete you can see it for yourself at [http://localhost:8012/football/teams](http://localhost:8012/football/teams).
There is also a CLI command available to populate your database with an Excel file.

## Installation

### Prerequisites

* PHP >= 8
* Composer
* Docker
* Docker-Compose

### Install Pimcore

1. Start the containers with Docker-Compose
    ```bash
    docker-compose up --detatch
    ```

2. Install necessary dependencies and enter app & mysql credentials
    ```bash
    docker exec -it <container-name> bash # Shell into container
    ./vendor/bin/pimcore-install --mysql-host-socket=db # Name of the MySQL docker container
    ```

3. Access Web Interface
    Go to `https://localhost:8012/admin` with the credentials you've entered the previous step.

### Install Bundle

This app is simply a bundle inside Pimcore, which also needs to be installed. We only need to install it and
run our migrations.

1. Install Bundle
    ```bash
    php bin/console pimcore:bundle:install AppBundle
    ```

2. Run migrations
    ```bash
    php bin/console doctrine:migrations:migrate --prefix=App\\Migrations
    ```

## Import Data via Command

You can import teams, trainers, etc. by simply creating an `.xlsx` file containing all necessary data.
Make sure the worksheets are structured properly. You can check out the example `example.xlsx` for a guide.

* `trainers`
    * `id`: `integer` (optional)
    * `first_name`: `string`
    * `last_name`: `string`
* `locations`
    * `id`: `integer` (optional)
    * `name`: `string` E.g. Berlin
    * `lat`: `float` Latitude
    * `lon`: `float` Longitude
* `player_positions`
    * `id`: `integer` (optional)
    * `name`: `string` Name of the position. E.g. "Mittelfeld"
* `teams`
    * `id`: `integer` (optional)
    * `name`: `string` Name of the team
    * `logo`: `string` URL to the logo image
    * `founded_at`: `integer`
    * `trainer`: `integer`/`string` ID or name of the trainer
    * `location`: `integer`/`string` ID or name of the location
* `players`
    * `id`: `integer` (optional)
    * `position`: `integer`/`string` ID or name of the position
    * `team`: `integer`/`string` ID or name of the team
    * `first_name`: `string`
    * `last_name`: `string`
    * `field_number`: `integer`
    * `age`: `integer`

The tables are also imported in the given order, to reduce conflicts.
Start the import, by running the command:

```bash
docker exec -it <container-name> bash # Shell into container
bin/console import:teams <file> [<sheetName>]
```

* `<file>`: Path from the root directory of the project to your file. E.g. 'example.xlsx'
* `[<sheetName>]`: Allows importing only a single sheet, instead of all.

### Application Usage

Simply open your web browser at [http://localhost:8012/football/teams](http://localhost:8012/football/teams) to view
all current teams in the database. It should look something along those lines:

![/docs/images/example_overview.png](/docs/images/example_overview.png)

Clicking on "Details" will show you a detailed view of the football team:

![/docs/images/example_item.png](/docs/images/example_item.png)
