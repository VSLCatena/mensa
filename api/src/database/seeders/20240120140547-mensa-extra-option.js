'use strict';
const { faker } = require('@faker-js/faker');

const generateMensaExtraOptions = (mensaId, amount = 1) => {
  const mensaExtraOptions = [];

  for (let i = 1; i <= amount; i++) {
    const mensaExtraOption = {
      mensa_id: mensaId,
      description: faker.lorem.words(3),
      price: faker.finance.amount(1, 5),
      created_at: new Date(),
      updated_at: new Date()
    };

    mensaExtraOptions.push(mensaExtraOption);
  }

  return mensaExtraOptions;
};

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.bulkInsert('mensa_extra_options', [
      ...generateMensaExtraOptions(2, 1)
    ], {});
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.bulkDelete('mensa_extra_options', null, {});
  }
};
