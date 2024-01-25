export class User {
  membershipNumber: string;
  name: string;
  mensaAdmin: boolean;
  vegetarian: boolean;
  serviceUser: boolean;
  email?: string;
  allergies?: string;
  extraInfo?: string;
  phoneNumber?: string;
  rememberToken?: string;

  constructor(
    membershipNumber: string,
    name: string,
    mensaAdmin: boolean,
        vegetarian: boolean,
    serviceUser: boolean,
            email?: string,
    allergies?: string,
    extraInfo?: string,
    phoneNumber?: string,
    rememberToken?: string,



  ) {
    this.membershipNumber = membershipNumber;
    this.name = name;
    this.mensaAdmin = mensaAdmin;
    this.vegetarian = vegetarian;
    this.serviceUser = serviceUser;
    this.email = email;
    this.allergies = allergies;
    this.extraInfo = extraInfo;
    this.phoneNumber = phoneNumber;
    this.rememberToken = rememberToken;
  }
}



