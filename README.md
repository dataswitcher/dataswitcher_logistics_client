# Dataswitcher Logistics Client

PHP client library to use the Logistics Online API.

Note: You need PHP 8.0. This package swisnl/json-api-client has a notice on PHP above 8.0 that breaks the tests.

## Install

Installing this Dataswitcher client for PHP can be done through Composer.  You need to add this to your composer.json file.

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/dataswitcher/dataswitcher_logistics_client"
    }
  ],
  "require": {
    "dataswitcher/dataswitcher_logistics_client": "^1.0"
  }
}
```

note: Use updated github tag version on "require" key.

## Usage

First, get the following from Auth0: client_id and client_secret. Steps:

- Authenticate in Auth0 with your dashboard account.
- Next choose tenant dataswitcher-dev (IMPORTANT) in top left menu.
- And go to Applications > APIs > Logistics API Dev > Tab Machine to Machine Applications > select Logistics API Dev (Test Application) > Get Client ID and Secret.

Then see the example file: [example/example.php](example/example.php) to see how to use this package.

## Tests

Copy .env.example to .env.testing.

Place the client id and client secret from Auth0 in .env.testing.

Then just run the command.

```sh
./bin/tests.sh
```

It only works in local environment, if you have any problem running the tests,  use this other script that adds the local DNS host mapping to `docker-run` command:

```sh
./bin/tests_local.sh
```
