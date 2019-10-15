# BileMo

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5e45a305cf8f40e59f2008805ff916c3)](https://www.codacy.com/manual/moezovic/BileMo_API?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moezovic/BileMo_API&amp;utm_campaign=Badge_Grade)

Is a B2B Restful API exposing endpoints to manage SmartPhones catalog  and customers ressources.

## Goals:

* Respect Rest API Architecture constraintes
* Serialize and deserialize ressources
* Configure authentification and authorization
* Associate technical documentation

## Framework - Bundles - Libraries:

* Symfony 4.3.4
* JMSSerializer
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

## Authentificating

1. After loading fixtures
2. Access the following URL

`````
localhost:8000/api/login_check
`````
3. Add the client's mail and password

``````
{
	"username":"client_(id)@gmail.com",
	"password": "client_(id)"
}
``````
