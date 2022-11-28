# Clippings documents calculation

## Setup and running the application

- copy .env.example as .env
- run `docker-compose up --build`
- run `docker-compose exec app bash` to enter the app container
  - `composer install` to install dependencies
  - `php artisan key:generate` to generate key for Laravel
  - `php artisan migrate` to migrate the tables

### Running tests
execute `./vendor/bin/phpunit` in the app container ( command `docker-compose exec app bash` can be used to enter the container )

### Hitting the application
App will be available at:

http://localhost:8088/api/v1/sumInvoices

filtering could be done with request parameter as follows:
- http://localhost:8088/api/v1/sumInvoices?vat=123456789

### Issues
- preg match for input is not correct in the assignment
  - incorrect one: `^([\w]){3}:\d*(.\d+)*$` (outputs as valid `1.8Y9`)
  - correct one: `^([\w]){3}:(\d+(?:[\.\,]\d*)?)$`

- outputCurrency uses different pattern than the response currency
  - ^([\w]){3}$
  - ^([A-Z]){3}$

- provided exchange rates are not in valid json format

- why do we have baseCurrency? we can calculate the indexes even if no base currency is set


## Extensions ideas
- return different types of messages for different validation errors
