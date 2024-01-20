'use strict';
import { faker } from '@faker-js/faker';

const generateMenuItems = (mensaId) => {
  const menuItems = [];

  for (let i = 1; i < 3; i++) {
    const menuItem = {
      mensaId: faker.number.int(mensaId), // Assuming mensa IDs are in the range 1-100
      order: i + 1,
      text: faker.lorem.words(3),
    };

    menuItems.push(menuItem);
  }

  return menuItems;
};

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.bulkInsert('menu-items', [
      ...generateMenuItems(1),
      ...generateMenuItems(2),
      ...generateMenuItems(3),
    ], {});
  },

  async down (queryInterface, Sequelize) {
await queryInterface.bulkDelete('menu-items', null, {});
  }
};
