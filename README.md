# Basic API application
This application is using ddev as conteinerization tool, 
Symfony as PHP framework and MySQL as database.

## Project setup
1. Clone the repository
2. Copy .env.example as .env and fill database credentials
3. ``ddev start``
4. Enter ssh with ``ddev ssh``
5. ``cd app``
6. ``composer install``
7. ``bin/console schema:database:create``
8. ``bin/console doctrine:migrations:migrate``

## Using the application
1. To create a coupon, send a POST request to ``/api/coupons``  with query parameters:
    - name: string
    - code: string
    - leftCount: int
    - isActive: bool
2. To get a list of all coupons, send a GET request to ``/api/coupons``
3. To get a single coupon, send a GET request to ``/api/coupons/{id}``
4. To update a coupon, send a PUT request to ``/api/coupons/{id}`` with query parameters:
    - name: string
    - isActive: bool
5. To delete a coupon, send a DELETE request to ``/api/coupons/{id}``

## Tests
#### First step is to copy ``.env.test`` as ``.env.test.local`` and fill database credentials

#### Before every test first execute command below to reset database:
- ``bin/console --env=test doctrine:fixtures:load --purge-with-truncate``

#### To run tests execute command below:
- ``php bin/phpunit tests/CouponControllerTest.php``
