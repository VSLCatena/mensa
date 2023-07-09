*Main*

[![Docker (Branch: Main)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml/badge.svg?branch=main-docker)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml)
[![Laravel (Branch: main)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml/badge.svg?branch=main-docker)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml)

*Development*

[![Docker (Branch: development)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml/badge.svg?branch=development)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml)
[![Laravel (Branch: development)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml/badge.svg?branch=development)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml)


# mensa
Mensa is an enrollment system for dinner that we use at our student association.
You can create dinners and assign them to cooks, who can change the dinner by adding a menu and changing some extra 
info.

The back-end works through a PHP server, the front-end is html/javascript which talks to back-end through AJAX calls.
This means that with very little adjustments you could split front-end from back-end.


## Setup

### .env file
All configuration of the app itself should be doable through the .env file.
If you don't have a .env file available, you can duplicate the .env.example file to create a new .env file.

### Authorization
For authorization we use Active Directory. For this you'll need to make sure PHP-LDAP works

### Building the app
For production I recommend to look at, and following the 
[Laravel deployment page](https://laravel.com/docs/8.x/deployment). 
Here you can see the laravel requirements and some optimizations you can do for Laravel such as caching options.

#### Here are just the bare minimals for development purposes:
To set up composer (Package manager for PHP):  
`composer install`

To set up npm (Package manager for Javascript):  
`npm install`  
`npm run dev`

To run migrations:  
`php artisan migrate`

To fill in mock data in the database:  
`php artisan db:seed`



## Running the app for development
For running the PHP server:  
`php artisan serve`  
This will keep a server running, and changes are reflected immediately.  

For compiling the front-end source:  
`npm run watch`  
This will keep npm running, a new build will automatically start on every file changes,
so changes are reflected on page refresh.

These two commands have made my life a lot better :)


## Docker
Info about how to get it working in docker can be found [Here](docs/DOCKER.md)