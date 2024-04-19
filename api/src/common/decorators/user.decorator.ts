import { createParamDecorator } from '@nestjs/common';
import { RequestExtended } from '../contracts/request-extended.contract';

export const UserDecorator = createParamDecorator(
  (data: unknown, req: RequestExtended) => {
    return req.user;
  },
);