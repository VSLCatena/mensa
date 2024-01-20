'use strict';
import { faker } from '@faker-js/faker';

const generateMensaUsers = (mensaId, amount) => {
  const mensaUsers = [];
  const usedMembershipNumbers = new Set();

  for (let i = 1; i <= amount; i++) {
    let membershipNumber;
    do {
      membershipNumber = faker.datatype.number({ min: 400000, max: 400100 });
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
      membershipNumber: membershipNumber,
      mensaId: mensaId,
      cooks: cook,
      dishwasher: dishwasher,
      isIntro: faker.datatype.boolean(0.1),
      allergies: faker.lorem.words(),
      extraInfo: faker.lorem.words(),
      confirmed: faker.datatype.boolean(),
      createdAt: new Date(),
      updatedAt: new Date(),
      deletedAt: null,
      confirmationCode: crypto.randomBytes(32).toString('hex'),
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
