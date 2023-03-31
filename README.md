# Pet Shop

This is an example app as part of Buckhill's selection process.

## Running locally

Copy `.env.example` to `.env`.

The provided `Dockerfile` and `docker-compose.yml` should handle the installation of the required services and dependencies.

For convenience, the public/private keys needed for JWT under `storage/app/jwt` is going to be included.

You can replace them with your own by following the guide for generating JWT key pair below.

```bash
docker compose up -d
```

If this is the first time you are running this app, you have to run the migrations and seeders to populate the database.

```bash
docker compose exec php php artisan migrate --seed
```

The app should be accessible via `http://localhost:8000`.

## Generate Public/Private key pair

You can use website like https://travistidwell.com/jsencrypt/demo/ for convenience.

You can also generate via CLI by doing:

```bash
ssh-keygen -t rsa -b 4096 -m PEM -f jwt.key
```

Put the generated files to `storage/app/jwt` as `jwt.key` for private key and `jwt.pub` for public key.

You can update `config/jwt.php` if you want to use different filenames.

## Generate IDE Helper

Because everything's handle by Docker, the process for generating `_ide_helper.php` file
is to run the command via the container and copying it to your host.

```bash
docker compose exec php php artisan ide-helper:generate
docker compose cp php:/var/www/html/_ide_helper.php .
```

## Larastan and PHP Insights

```bash
docker compose exec php php artisan insights
docker compose exec php vendor/bin/phpstan analyse --memory-limit=1G
```

Memory limit is needed to prevent memory exhaustion

## Running the tests

```bash
docker compose exec php php artisan test
```

## Level 3 Challenge (Currency exchange rate)

The package required for this challenge can be found under `packages/exchange` directory.

You can check the included `README.md` for more info.

## Level 4 Challenge (Order Status Notification)

The package required for this challenge can be found under `packages/notification` directory.

You can check the included `README.md` for more info.

## Level 5 Challenge (State Machine Trait)

The package required for this challenge can be found under `packages/state-machine` directory.

You can check the included `README.md` for more info.
