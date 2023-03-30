# Pet Shop

This is an example app as part of Buckhill's selection process.

## Running locally

The provided `Dockerfile` and `docker-compose.yml` should handle the installation of the required services and dependencies.

```bash
docker compose up -d
```

If this is the first time you are running this app, you have to run the migrations and seeders to populate the database.

```bash
docker compose exec php php artisan migrate --seed
```

The app should be accessible via `http://localhost:8000`.

## Level 3 Challenge (Currency exchange rate)

The package required for this challenge can be found under `packages/exchange` directory.

You can check the included `README.md` for more info.

## Level 4 Challenge (Order Status Notification)

The package required for this challenge can be found under `packages/notification` directory.

You can check the included `README.md` for more info.
