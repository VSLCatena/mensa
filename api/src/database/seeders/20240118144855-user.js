'use strict';
import { faker } from '@faker-js/faker';

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
        membershipNumber: '222222',
        name: 'user',
        email: 'user@vslcatena.nl',
        mensaAdmin: false,
        phoneNumber: faker.phone.phoneNumber(),
        vegetarian: true,
        serviceUser: false,
        createdAt: new Date(),
        updatedAt: new Date(),
      }
    ], {});
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.bulkDelete('users', null, {});
  }
};