import { StaffRole } from "../types/staff-role.type";

export class Staff {
	name: string;
    role: StaffRole;

	constructor(
		name: string,
		role: StaffRole,
	) {
        this.name = name;
        this.role = role;
	}
}