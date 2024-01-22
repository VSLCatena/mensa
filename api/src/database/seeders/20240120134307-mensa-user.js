'use strict';
const { faker } = require('@faker-js/faker');
const crypto = require('crypto');

const generateMensaUsers = (mensaId, amount) => {
  const mensaUsers = [];
  const usedMembershipNumbers = new Set();

  for (let i = 1; i <= amount; i++) {
    let membershipNumber;
    do {
      membershipNumber = faker.number.int({ min: 400000, max: 400100 });
    } while (usedMembershipNumbers.has(membershipNumber));

    usedMembershipNumbers.add(membershipNumber);
    let cook = false;
    let dishwasher = false;

    if (i === parseInt(amount / 2)) {
      cook = true;
    }
    if (i === 1) {
      dishwasher = true;
    }

    const mensaUser = {
      membership_number: membershipNumber,
      mensa_id: mensaId,
      cooks: cook,
      dishwasher: dishwasher,
      is_intro: faker.datatype.boolean(0.1),
      allergies: faker.lorem.words(),
      extra_info: faker.lorem.words(),
      confirmed: faker.datatype.boolean(),
      created_at: new Date(),
      updated_at: new Date(),
      deleted_at: null,
      confirmation_code: crypto.randomBytes(32).toString('hex'),
      vegetarian: faker.datatype.boolean(0.4),
    };

    mensaUsers.push(mensaUser);
  }

  return mensaUsers;
};

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.bulkInsert('mensa_users', [
      ...generateMensaUsers(1, 10),
      ...generateMensaUsers(2, 15),
      ...generateMensaUsers(3, 5),
    ], {});
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.bulkDelete('mensa_users', null, {});
  }
};