# Affluence
Make an application to prevent waiting time in stores.

That's the API project who manage the database and send data to the application.

## Basic command

Download project : `git clone https://github.com/sckraa/Affluence.git`

Place it in your repertory `www/` of your Apache stack.

Install project : `composer install`

Update project : `composer update`

Add your database connection information in the `.env` file.

Create database: `php bin/console doctrine:database:create`

Structure database: `php bin/console doctrine:migrations:migrate`

You can now use the API :)

## API Requests

! The requests documentation is in French !

* [User](https://docs.google.com/document/d/10zgbaZNTKxMlsN-pYFG2kU3zGOuf3cmxcrUtdwvl1B8/edit?usp=sharing)
* [Store](https://docs.google.com/document/d/1VOv7HtRVUajX3LnsGMXvtd4j2aoLPmh2RS5CR4wCgq4/edit?usp=sharing)
* [Waiting line](https://docs.google.com/document/d/1m2IgehwXi8O1K6WoLH9jbiTMjWQLCpkIdDJ-v9oi0kc/edit?usp=sharing)
* [Waiting line informations](https://docs.google.com/document/d/19aifh2Kfq6_QbCQncofA6I3spbXzDD0Wk95chMNyhZM/edit?usp=sharing)

## Team

* [Balzac Baudemont](https://github.com/balzacbdmt)
* [Alexis Granger](https://github.com/agranger13)
* [Ion Luca](https://github.com/sckraa)
* [Nathan Wolf](https://github.com/Wolf-Nathan)

## Affluence Application Project

On this page you can find the application connect with this API :
https://github.com/sckraa/Affluence-Front