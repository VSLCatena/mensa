# Mensa
Mensa is a versatile enrollment system designed for managing dinner events within our student association.
With Mensa, you have the flexibility to create dinners and assign them to cooks. Cooks can then enhance the
dinner experience by adding menus and updating additional information as needed.

## Setup
The setup process is fairly simple. You need:
 - Docker

### Installation
Run ```npm install``` in directory **web** and **api** to install all dependencies. <br>
If you **don't** have NPM installed,  you can use ```docker compose -f docker-install.yml up && docker compose -f docker-install.yml down```
inside the **docker** directory to install all dependencies for api and web.

### ENV / Secrets
Duplicate the **.env.example** file and rename it to **.env**. The env example file contains all the environment variables
needed to run the project.

### Running the project
Go to the **docker** directory and run ```docker compose up```.
This will start up all the needed services for the project.
 - Web -> http://localhost:4200
 - API -> http://localhost:3000
 - Database -> http://localhost:3306 (Need to use a database client to connect to it)

## Techstack
This section will provide a brief overview of the techstack used within the project.
 - Web -> Angular (V17)
 - API -> NestJS (V10)
 - Database -> MariaDB (V10)
 - Node -> v20 (lts)

### API Documentation
The API documentation can be found in the **api/README.md** file.
It contains all information about the API so you can start developing right away.

### Web Documentation
The Web documentation can be found in the **web/README.md** file.
It contains all information about the Web application so you can start developing right away.