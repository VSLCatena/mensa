
import { Injectable, NestMiddleware } from '@nestjs/common';
import { Response, NextFunction } from 'express';
import { RequestExtended } from 'src/common/contracts/request-extended.contract';
import { User } from 'src/database/models/user.model';

@Injectable()
export class UserMiddleware implements NestMiddleware {
  use(req: RequestExtended, res: Response, next: NextFunction) {
    // TODO Catch the user from the database.
    req.user = User.build({
        id: 1,
        name: 'John Deer',
        email: 'johndeer@hoolie.us',
        allergies: 'nuts, milk, gluten',
        vegetarian: true,
        mensaAdmin: true,
        serviceUser: false,
        membershipNumber: '1234567890'
    });
    next();
  }
}