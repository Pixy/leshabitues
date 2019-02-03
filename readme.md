# Installation

## If you use Docker

Clone the repository, then use the Makefile to test the application.

```bash
# Init your .env file, the example file already has correct configuration to use the app with docker
cp app/.env.example app/.env

# Build the app
make build

# Start DB
make init

# Launch migrations
make migrate

# Run unit tests
make tests

# Run the import command, can be used several times
make import
```


## If you don't use Docker

You will need a mysql server in your local machine. In this server, create a database named `leshabitues`.
Init your `.env` file by running `cp app/.env.example app/.env`. In the `.env` file you will need to replace database informations.
- `DB_HOST` will be your mysql server host
- Replace `DB_USERNAME` and `DB_PASSWORD` with your own configuration.

In the `app` folder, you will need to run 
````bash
# Install dependancies
composer install

# Init the database
php artisan migrate

# Run the unit tests
php vendor/bin/phpunit

# Run the app
php artisan shops:import-list
````


# Explanations

I choose Lumen (light version of laravel) for this test because I'm comfortable with it.

I've created a Shop model, respecting the JSON file, no additional entities, but we can thing about creating a `Category` entity for the future.
The structure can be found in the migration in `app/database/migrations/2019_02_02_150717_create_shops_table.php`.

I first tried to think about a way to update data only when it will be necessary. I choose to create a `hash`, stored directly in the model, in order to compare data fetched from the JSON and what we have previously stored.
The `hash` is calculated with the combination of different fields of the model, excepted the `id`, the timestamps (`created_at` and `updated_at`), and of course the `hash` itself.
The hash is automatically computed and stored when the model is created.
You can find the generation of the hash in the file `app/app/Helpers/helpers.php`.
The automatic creation is handled directly in the model `app/app/Shop.php`, in the `boot` function. We can access model's callbacks in this function.

The main application is written in `app/app/Console/Commands/ImportShopList.php`. I preferred to use a command rather than a controller because I think it's a worker, that will be launched by a cron, not triggered by API.
 
The worker first fetches the JSON file from the URL. From this data, we get IDs of shops, and get our own shops stored in our DB. 
This will be used to see if we have to create or update the shop, or even do nothing.
If we already stored the shop, we generate a hash from the data we just fetched, using the same function in the helper file, and compare it with the existing one in our DB.
We update the shop only if hashes are not equals. 

The idea is to limit access to DB, fetching only once at first our shops, and updating only when it's necessary.
