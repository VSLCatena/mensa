<p align="center">
  <a href="http://nestjs.com/" target="blank"><img src="https://nestjs.com/img/logo-small.svg" width="200" alt="Nest Logo" /></a>
</p>

[circleci-image]: https://img.shields.io/circleci/build/github/nestjs/nest/master?token=abc123def456
[circleci-url]: https://circleci.com/gh/nestjs/nest

  <p align="center">A progressive <a href="http://nodejs.org" target="_blank">Node.js</a> framework for building efficient and scalable server-side applications.</p>
    <p align="center">
<a href="https://www.npmjs.com/~nestjscore" target="_blank"><img src="https://img.shields.io/npm/v/@nestjs/core.svg" alt="NPM Version" /></a>
<a href="https://www.npmjs.com/~nestjscore" target="_blank"><img src="https://img.shields.io/npm/l/@nestjs/core.svg" alt="Package License" /></a>
<a href="https://www.npmjs.com/~nestjscore" target="_blank"><img src="https://img.shields.io/npm/dm/@nestjs/common.svg" alt="NPM Downloads" /></a>
<a href="https://circleci.com/gh/nestjs/nest" target="_blank"><img src="https://img.shields.io/circleci/build/github/nestjs/nest/master" alt="CircleCI" /></a>
<a href="https://coveralls.io/github/nestjs/nest?branch=master" target="_blank"><img src="https://coveralls.io/repos/github/nestjs/nest/badge.svg?branch=master#9" alt="Coverage" /></a>
<a href="https://discord.gg/G7Qnnhy" target="_blank"><img src="https://img.shields.io/badge/discord-online-brightgreen.svg" alt="Discord"/></a>
<a href="https://opencollective.com/nest#backer" target="_blank"><img src="https://opencollective.com/nest/backers/badge.svg" alt="Backers on Open Collective" /></a>
<a href="https://opencollective.com/nest#sponsor" target="_blank"><img src="https://opencollective.com/nest/sponsors/badge.svg" alt="Sponsors on Open Collective" /></a>
  <a href="https://paypal.me/kamilmysliwiec" target="_blank"><img src="https://img.shields.io/badge/Donate-PayPal-ff3f59.svg"/></a>
    <a href="https://opencollective.com/nest#sponsor"  target="_blank"><img src="https://img.shields.io/badge/Support%20us-Open%20Collective-41B883.svg" alt="Support us"></a>
  <a href="https://twitter.com/nestframework" target="_blank"><img src="https://img.shields.io/twitter/follow/nestframework.svg?style=social&label=Follow"></a>
</p>

# Documentation
The API is documented with Swagger, this is a tool that generates documentation based on the code.
This is available at http://localhost:3000/swagger, when the API is running.

The rest of documentation is inside this readme.

## Database / Migrations / Models
The database is managed by Sequelize, this is an ORM (Object Relational Mapper).
In this section will be explained how new models can be created and updated.

### Creating a new model
To create a new model, you need to create a new file in the **api/src/models** directory.
The pattern *modelname.model.ts* is used for naming the files.

Documentation for creating a model can be found here: https://www.npmjs.com/package/sequelize-typescript#model-definition

### Registering the model
After creating a model it needs to be registered. This can be done by adding the model to the ```export const models = [...]``` inside the
**api/src/database/models.database.ts** file. This will register the model and make it available as repository in the api.

#### Creating a migration
After creating the model, you need to create a migration for it.
This can be done by running the following command: ```npm run migration:generate mymigrationname```<br>
Inside **api/src/database/migrations** a new file will be created, you have to add the needed code to create the table.

Documentation for creating a migration can be found here: https://sequelize.org/docs/v6/other-topics/migrations/#migration-skeleton

#### Creating a seeder
Seeders are used to populate the database with data, this is useful for testing purposes.
This can be done by running the following command: ```npm run seed:generate myseedname```<br>
Inside **api/src/database/seeders** a new file will be created, you have to add the needed code to populate the table.

Documentation for creating a seeder can be found here: https://sequelize.org/docs/v6/other-topics/migrations/#creating-the-first-seed

##### When do I create a seeder?
You create a seeder when you have added a new model to the database and you want to populate it with data.
When updating an existing model you don't need to create a seeder, you can just update the existing one.

### Using the model
When the model is created and registered you need to create a **repository** for it to communicate with the database.
To create a repository you need to create a new file in the **api/src/repositories** directory with the pattern *modelname.repository.ts*.

You can use the following template to create a repository for your model.
The base repository covers all the basic CRUD operations, so you don't have to write them yourself.
```typescript
import { Injectable } from '@nestjs/common';
import { BaseRepository } from './base.repository';
import { MODELNAME } from 'src/database/models/MODELNAME.model';
import { InjectModel } from '@nestjs/sequelize';

@Injectable()
export class MODELNAMERepository extends BaseRepository<MODELNAME> {
	constructor(
		@InjectModel(MODELNAME)
		readonly model: typeof MODELNAME
	) {
		super(model);
	}
}
```

After creating the repository you need to register it in the **api/src/common/common.module.ts** file by adding it to the ```const repositories = [...]``` array.

### Updating a model
When updating a model you need to take the following steps:
 - Update the model
 - Update the migration
 - Update the seeder
 - Update the repository (if needed there is custom stuff in there)

Also be sure to update the dto's in the api and models in the web if needed.


## Modules
The API is build with modules, this is a way of structuring the code.
A module is placed inside the **api/src/modules** directory, and can be created by running the following command: ```npx nest generate module modules/mymodulename```.
Each module is for a specific part of the API, for example the **auth** module is for authentication.
The structure of a module looks like this:
 - /controller
   - Contains the controller with routes for the module.
 - /dto
   - Contains the dto's for the module.
 - /service
   - Contains the service for the module which is only for the module otherwise create a service in **common/services**.
 - mymodule.module.ts

To use the injectable services from the common module (like the **repositories**) you need to add the **CommonModule** to the imports of the module.

## Validation
Validation is done with the class-validator package, this is a package that allows you to validate objects.
See the documentation for more information here: https://www.npmjs.com/package/class-validator

The validation properties is used on the models see the DTO's and models in the database.

### Use validation
If you're controller needs to validate a request be sure to add this above the function:
```typescript
@UsePipes(new ValidationPipe())
```