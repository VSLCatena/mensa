import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import { User } from 'src/database/models/user.model';

@Injectable()
export class UserService {

    constructor(
        @InjectModel(User)
        private readonly userModel: typeof User
    ) {}

    findByMembershipNumber(membershipNumber: string): Promise<User> {
        return this.userModel.findOne({
            where: {
                membershipNumber: membershipNumber
            }
        });
    }
}
