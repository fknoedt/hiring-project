# Simple Slim Sample (SSS) Hiring Project

## Disclaimer

This project was implemented in 3 days so I had to overlook some parts that would get more attention otherwise.

Many of the conventions (autoload, directories, configs, file, class naming) were taken from Laravel and Symfony but the structure was built from scratch using Slim 4.3.0 and the components below.

## composer dependencies required
 ```
{
  "slim/slim": "^4.0",
  "slim/http": "^0.8.0",
  "slim/psr7": "^0.6.0",
  "vlucas/phpdotenv": "^3.6",
  "php-di/slim-bridge": "^3.0",
  "slim/twig-view": "^2.5",
  "larapack/dd": "^1.1",
  "illuminate/database": "~5.1"
}
  ```

## Database

MySQL was the chosen database engine. The DB Schema and Questions & Answers can be found in the `/database` folder in this repository.

## Bootstrap
  * login page layout:  https://bootsnipp.com/snippets/emRPM
  * bootstrap-select: https://developer.snapappointments.com/bootstrap-select/

## Environment

To setup the environment configuration, create a `.env` file on the root directory with this content set to your environment:
```
DB_HOST=127.0.0.1
DB_DATABASE=sss
DB_USERNAME=root
DB_PASSWORD=
DB_CONNECTION=
DB_DRIVER=mysql
DB_PORT=3306
```

## Demo

You can see the project live here: https://sss.filipe.knoedt.net (user: `demo` | pass: `20191027`)
