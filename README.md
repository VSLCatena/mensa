# Mensa
Mensa is a versatile enrollment system designed for managing dinner events within our student association.
With Mensa, you have the flexibility to create dinners and assign them to cooks. Cooks can then enhance the
dinner experience by adding menus and updating additional information as needed.

## Setup
The setup process is fairly simple. You need:
 - NPM (Node Package Manager) (Project uses node v20)
 - Docker

### Installation
Run ```npm install``` in directory **web** and **api** to install all dependencies.

### ENV / Secrets
Duplicate the **.env.example** file and rename it to **.env**. The env example file contains all the environment variables
needed to run the project.

### Running the project
Go to the **docker** directory and run ```docker-compose up```.
This will start up all the needed services for the project.
 - Web -> http://localhost:4200
 - API -> http://localhost:3000
 - Database -> http://localhost:3306 (Need to use a database client to connect to it)