import { User } from "src/database/models/user.model";

export interface RequestExtended extends Request {
    user: User;
}