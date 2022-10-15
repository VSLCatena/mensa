[![Docker (Branch: feature/docker)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml/badge.svg?branch=feature%2docker)](https://github.com/VSLCatena/mensa/actions/workflows/docker-publish.yml)
[![Laravel (Branch: feature/revamp)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml/badge.svg?branch=feature%2Frevamp)](https://github.com/VSLCatena/mensa/actions/workflows/laravel.yml)
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
For authorization we use OAuth for Azure Active Directory. For this you'll need to register an app in Azure Active 
Directory. You can do this by going to Azure Active Directory -> App registrations -> New registration.

#### Setup
- In _Authentication_ you will need to add a **redirectUri**. For debugging you could add for example 
  `http://localhost:8000/login/token`
- In _Api permissions_ you'll need to give the app the permissions: `GroupMember.Read.All`, `User.Read` and 
  `User.Read.All`
- In _Certificates & secrets_ you will have to create a **New client secret**. Save the secret you receive.

#### Now in .env you'll have to add some values:
Unless specified, all of these values can be grabbed from the _Overview_ tab on the App page in Azure AD.
- _AZURE_TENANT_ID_ is **Directory (tenant) ID**
- _AZURE_CLIENT_ID_ is **Application (client) ID**
- _AZURE_CLIENT_SECRET_ is the **secret** you received from creating a new secret in _Certificates & secrets_
- _AZURE_REDIRECT_URI_ is the **redirectUri** you filled in in the _Authentication_ tab.

The variables with the **REMOTE_USER_** prefix work a little bit different.
- For _REMOTE_USER_ADMIN_GROUP_ID_ Choose or create an Azure group that will contain all the admins of the application.
  The **Object Id** is what you'll need for the .env file.
- _REMOTE_USER_UPDATE_TIME_ is the amount of seconds it will wait before grabbing the user from the remote server 
  (Azure) again. This is to reduce the amount of Azure calls. 
  - A log in always does an Azure call, but an already logged in user waits this amount of seconds before it checks on
    the remote server again. So keep in mind that revoking permissions is not instantaneous!
- _REMOTE_USER_EMAIL_SUFFIX_ is the email suffix for our principial name. We allow people to give up their email or 
  their username. The only way to get the user by username is by pricipial name, which is for us: 
  `username@vslcatena.nl`. So for us this would be `@vslcatena.nl`.

### Building the app (using Sail)
- Docker using [Docker Docs](https://docs.docker.com/desktop/install/linux-install/)
- Sail using [Sail Documentation](https://laravel.com/docs/9.x/sail)

Some important Sail commands:
- `./vendor/bin/sail up` to start Sail in foreground
- `./vendor/bin/sail up -d` to start Sail in background
- `./vendor/bin/sail ps` to see current status
- `./vendor/bin/sail artisan` Run an Artisan command
- `./vendor/bin/sail composer` Run an composer command
- `./vendor/bin/sail root-shell`  Start a root shell session within the application container
- `./vendor/bin/sail shell` Start a shell session within the application container
#### Permissions (when using safe-docker / Rootless mode)
- `drwxrwxr-x  <items> <in-container uid> <regular username>  <size> <timestamp> FOLDER` 
  - Sail Artisan can create files 
- `-rw-rw-r--  <items> <regular username> <regular username>  <size> <timestamp> FILE`  
  - You can upload files using sftp

### Building the app (using regular webserver)
For production I recommend to look at, and following the 
[Laravel deployment page](https://laravel.com/docs/9.x/deployment). 
Here you can see the laravel requirements and some optimizations you can do for Laravel such as caching options.

### Configure and prepare the dependencies:
To set up composer (Package manager for PHP):  
`composer install`

To set up npm/yarn (Package managers for Javascript):  
`npm install`  
`yarn install`  
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
