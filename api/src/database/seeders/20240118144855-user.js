'use strict';
const { faker } = require('@faker-js/faker');

const generateUsers = () => {
  const users = [];

  for (let i = 400000; i <= 400100; i++) {
    const user = {
      membership_number: i.toString(),
      name: faker.person.fullName(),
      email: faker.internet.email(),
      mensa_admin: false,
      phone_number: faker.phone.number(),
      vegetarian: faker.datatype.boolean(),
      extra_info: faker.lorem.words({ min: 0, max: 5 }),
      service_user: false,
      created_at: new Date(),
      updated_at: new Date(),
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
        membership_number: '111111',
        name: 'admin',
        email: 'admin@vslcatena.nl',
        mensa_admin: true,
        phone_number: faker.phone.number(),
        vegetarian: true,
        service_user: true,
        created_at: new Date(),
        updated_at: new Date(),
      },
      {
        membership_number: '222222',
        name: 'serviceuser',
        email: 'serviceuser@vslcatena.nl',
        mensa_admin: false,
        phone_number: faker.phone.number(),
        vegetarian: true,
        service_user: true,
        created_at: new Date(),
        updated_at: new Date(),
      },
      {
        membership_number: '333333',
        name: 'user',
        email: 'user@vslcatena.nl',
        mensa_admin: false,
        phone_number: faker.phone.number(),
        vegetarian: true,
        service_user: false,
        created_at: new Date(),
        updated_at: new Date(),
      }, ...generateUsers()
    ], {});
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.bulkDelete('users', null, {});
  }
};