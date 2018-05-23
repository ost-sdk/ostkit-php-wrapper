
# OST KIT - PHP Wrapper for the OST KIT API

A PHP wrapper for the REST API of [OST KIT](https://kit.ost.com) which is currently under active development. This client implements version 0.9.2 of the [OST KIT REST API](https://dev.ost.com).

![Screenshot](ostkit.png)

A Branded Token economy must be setup first in order to use the API, see https://kit.ost.com for more information.

## How to install
```sh
composer require ostapiwrapper/ostkit:"dev-master"
```

## How to use the client

Create the OST KIT client using your Branded Token economy's `API key`, `API secret` and `API URL` default `https://sandboxapi.ost.com/v1`
```php
use Ostkit\Ostkit as Ostkit;

$ost = new Ostkit('YOUR-API-KEY', 'YOUR-API-SECRET');


/* USERS functions */
// Create a user named 'Ria'.
$response = $ost->userCreate('Luong');

// ... and rename 'Ria' to 'Fred'.
$response = $ost->userEdit($user['uuid'], 'Tinh');

// ... get info by id
$response = $ost->userRetrieve($user['uuid']); 

$response = $ost->userList(true); // lists all users

```
To be continues...

##How to run tests

In order to test the library:

Create a fork
Clone the fork to your machine
Install the depencies `composer install`
Run the unit tests `./vendor/bin/phpunit tests`







