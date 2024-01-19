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

## API
This concerns documentation for the API, this is build with NestJS which uses typescript.
See it as NodeJS with extra abstraction and types, it works the same way.
Within this section all the needed technical information will be provided to achieve maximum productivity
for new features or bug fixes.

### Database / Migrations / Models
The database is managed by Sequelize, this is an ORM (Object Relational Mapper).
In this section will be explained how new models can be created and updated.

#### Creating a new model
To create a new model, you need to create a new file in the **api/src/models** directory.
The pattern *modelname.model.ts* is used for naming the files.

Documentation for creating a model can be found here: https://www.npmjs.com/package/sequelize-typescript#model-definition

##### Creating a migration
After creating the model, you need to create a migration for it.
This can be done by running the following command: ```npm run migration:generate mymigrationname```<br>
Inside **api/src/database/migrations** a new file will be created, you have to add the needed code to create the table.

Documentation for creating a migration can be found here: https://sequelize.org/docs/v6/other-topics/migrations/#migration-skeleton