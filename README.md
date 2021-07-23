## Laratest Installation

To install this app you must first of all clone this repo by runing the command below
```
git clone https://github.com/bleriotnoguia/laratest.git
```

## Env Variables

Create a .env file in the root directory by copying .env.example
Then install all packages and generate the APP_KEY by runing the command below

```
$ composer install
$ php artisan key:generate 
```

Change the following value with your corresponding database credentials.

```
DB_USERNAME=xxxxxxxxxx
DB_PASSWORD=xxxxxxxx
```

Then create a database for this app. You can name it "laratest" as a default name or use a custom name but in this case you must update you DB_DATABASE value in .env

```
DB_DATABASE=laratest // or your custom name
```

To create all table of this app you must run 
```
php artisan make migration
```

Then execute the `passport:install` in other to create the encryption keys needed to generate secure access tokens
```
passport:install
```

Run the command below then open http://localhost:8000/
```
php artisan serve
```

