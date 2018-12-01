# money-management

## Development

If you are working with the team and it is your first time running this application to start developing it, relax. You just need to reproduce a few steps first.

Before we start, make sure you are on the project directory: `$ cd path/to/the/project`

### Installing the dependencies

1. Install Composer on your computer and **make sure** you renamed `composer.phar` to `composer` and moved it to the `bin` directory.
2. Create a file named `.env`. It should be a copy of `.env.example` for now.
3. Run the command: `$ php bin/composer install`.

After these steps, a new file is created in your root directory: `vendor`. It contains all our dependencies installed and managed by Composer.

### Creating your Database structure

1. Modify your `DATABASE_URL` config in `.env` file.
2. Configure the `driver` as `pdo_mysql` and `server_version` as `5.7` in `config/packages/doctrine.yaml`.
3. Create your database: `$ php bin/console doctrine:database:create`.
4. Run all the migrations: `$ php bin/console doctrine:migrations:migrate`.

After these steps, your Database should be created and populated with a few tables.

### Running the application

1. Start your server: `$ php -S 127.0.0.1:8000 -t public` **OR**;
2. Use Composer's server: `$ php bin/composer require server --dev`.

You **do not** need to use both of them. Choose only one option (we usually use the 1st one).

### Testing the application

1. Write your tests in the `tests/` folder.
2. Run `$ php bin/phpunit`.

### Changing your Entities

Whenever you need to change anything in your database structure (e.g. changing columns of an entity), you also need to create migrations.

Migration is a concept. It saves your database's change history, so the latest changes can also be executed by the other developers.

If you to change any Entity, the following steps must be followed:

1. Generate the migration file: `$ php bin/console make:migration`.
2. Run the recently created migration: `$ php bin/console doctrine:migrations:migrate`.

More info can be found at [Symfony's official docs](https://symfony.com/doc/4.1/doctrine.html).