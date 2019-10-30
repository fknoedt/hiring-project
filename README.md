# Simple Slim Sample (SSS) Hiring Project

## Disclaimer

This project was implemented in 3 days so I couldn't be more detailist and had to overlook some parts that would definitely get more attention otherwise.

Many of the conventions (autoload, directories, configs, file and class naming) were taken from Laravel and Symfony but the structure was built from scratch - not based on any existing structure - with Slim and the components below.

## Slim
  * version 4.3 (latest stable)
  * tried two different skeleton applications but went lean
  * spent more time than expected learning Slim and elaborating a backend architecture but got a good understanding of the framework and enjoyed it

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

MySQL was the chosen database engine. The DB Schema and Questions & Answers can be found on the `/database` folder on this repository.

## Bootstrap
  * login page layout:  https://bootsnipp.com/snippets/emRPM
  * bootstrap-select: https://developer.snapappointments.com/bootstrap-select/

## Environment

To setup the environment configuration,