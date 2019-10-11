# BileMo

Is a B2B Restful API exposing endpoints to manage SmartPhones catalog  and customers ressources.

## Goals:

* Respect Rest API Architecture constraintes
* Serialize and deserialize ressources
* Configure authentification and authorization
* Associate technical documentation

## Framework - Bundles - Libraries:

* Symfony 4.3.4
* FOSRestBundle
* LexikJWTAuthenticationBundle
* NelmioApiDocBundle
* BazingaHateoasBundle

## Installation:

1. Clone the repository on local machine

````
git clone https://github.com/moezovic/BileMo_API.git
````

2. Install the project using composer

````
composer install
````

3. Create database - Customize .env file - load fixtures

````
php bin/console doctrine:fixtures:load
````
4. Generate SSH keys for the LexikJWTAuthenticationBundle

`````
$ mkdir config/jwt
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
`````
You will be prompted to type a paraphrase for the first key.
Use the paraphrase for the second key