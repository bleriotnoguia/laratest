## Laratest Installation

To install this app you must first clone this repository by running the command below
```
git clone https://github.com/bleriotnoguia/laratest.git
```

Create a .env file in the root directory by copying .env.example
Then install all packages and generate the APP_KEY by running the command below

```
$ composer install
$ php artisan key:generate 
```

Change the following value with your corresponding database credentials.

```
DB_USERNAME=xxxxxxxxxx
DB_PASSWORD=xxxxxxxx
```

Then create a database for this app. You can name it "laratest" as a default name or use a custom name but in this case you need update your DB_DATABASE value in .env file

```
DB_DATABASE=laratest // or your custom name
```

Run the command below to create all the tables needed by this app 
```
php artisan make migration
```

Then execute `passport:install` in other to create the encryption keys needed to generate secure access tokens
```
passport:install
```
To create 2 default users run the command below
```
php artisan db:seed
```

Update your MAIL_* variable in other to match your mailtrap or mailhog configuration
```
MAIL_MAILER=smtp
MAIL_HOST=mailhog // or smtp.mailtrap.io
MAIL_PORT=1025 // or 2525
MAIL_USERNAME=null // or xxxxxxxxx
MAIL_PASSWORD=null // or xxxxxxxxx
MAIL_ENCRYPTION=null // or xxxxxxxxx
```

Open your database client and grab the password oauth client from the oauth_clients table, get the client id and secret and put them in the .env file
```
PASSPORT_CLIENT_ID=2
PASSPORT_CLIENT_SECRET=xxxxxxx
```

Run the command below then open http://localhost:8000/
```
php artisan serve
```

## API

### Login with email & password

- Url: http://localhost:8000/api/login
- Method : POST

> Headers

```
Content-Type: application/json
```
> Body (example)

```
{
    "email" : "guest@example.com",
    "password" : 12345678
}
```

### Login with email

Note : Only the user whose `email_auth_authorized` property is set to true can use this method. The field `email_auth_authorized` can be found in the users table.

- Url: http://localhost:8000/api/email/login
- Method : POST

> Headers

```
Content-Type: application/json
```
> Body (example)

```
{
    "email" : "guest@example.com"
}
```

Note : After submitting your email, you will receive an authentication link by email. Then you just have to click on it to login