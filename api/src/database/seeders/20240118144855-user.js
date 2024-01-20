'use strict';
const { faker } = require('@faker-js/faker');

const generateUsers = () => {
  const users = [];

  for (let i = 400000; i <= 400100; i++) {
    const user = {
      membershipNumber: i.toString(),
      name: faker.name.findName(),
      email: faker.internet.email(),
      mensaAdmin: false,
      phoneNumber: faker.phone.phoneNumber(),
      vegetarian: faker.datatype.boolean(),
      serviceUser: false,
      createdAt: new Date(),
      updatedAt: new Date(),
    };

    users.push(user);
  }

  return users;
};

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.bulkInsert('users', [
      {
        membershipNumber: '111111',
        name: 'admin',
        email: 'admin@vslcatena.nl',
        mensaAdmin: true,
        phoneNumber: faker.phone.phoneNumber(),
        vegetarian: true,
        serviceUser: true,
        createdAt: new Date(),
        updatedAt: new Date(),
      },
      {
        membershipNumber: '222222',
        name: 'serviceuser',
        email: 'serviceuser@vslcatena.nl',
        mensaAdmin: false,
        phoneNumber: faker.phone.phoneNumber(),
        vegetarian: true,
        serviceUser: true,
        createdAt: new Date(),
        updatedAt: new Date(),
      },
      {
        membershipNumber: '333333',
        name: 'user',
        email: 'user@vslcatena.nl',
        mensaAdmin: false,
        phoneNumber: faker.phone.phoneNumber(),
        vegetarian: true,
        serviceUser: false,
        createdAt: new Date(),
        updatedAt: new Date(),
      }, ...generateUsers()
    ], {});
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.bulkDelete('users', null, {});
  }
};