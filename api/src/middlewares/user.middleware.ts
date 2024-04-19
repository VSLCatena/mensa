
import { Injectable, NestMiddleware } from '@nestjs/common';
import { Response, NextFunction } from 'express';
import { RequestExtended } from 'src/common/contracts/request-extended.contract';
import { User } from 'src/database/models/user.model';

const defaultUser = new User();
defaultUser.id = 1;
defaultUser.name = 'John Deer';
defaultUser.email = 'johndeer@hoolie.us';
defaultUser.allergies = 'nuts, milk, gluten';
defaultUser.vegetarian = true;
defaultUser.mensaAdmin = true;
defaultUser.serviceUser = false;
defaultUser.membershipNumber = '1234567890';


@Injectable()
export class UserMiddleware implements NestMiddleware {
  use(req: RequestExtended, res: Response, next: NextFunction) {
    // TODO Catch the user from the database.
    req.user = defaultUser;
    next();
  }
}