'use strict';
import { faker } from '@faker-js/faker';

const today = new Date();

const generateMensae = (id, title, mensaDate, closingHour = 15) => {
  return {
    id: id,
    title: title,
    date: mensaDate,
    closingTime: mensaDate.setHours(closingHour, 0, 0, 0),
    maxUsers: faker.number.int({min: 15, max: 45}),
    price: 4.50,
    closed: isDateInPast(mensaDate),
    createdAt: mensaDate.setDate(mensaDate.getDate() - 7), // 7 days ago
    updatedAt: mensaDate.setDate(mensaDate.getDate() - 7) // 7 days ago
  }
}

function isDateInPast(inputDate) {
  const currentDate = new Date();
  return inputDate < currentDate;
}


/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.bulkInsert('mensas', [
      generateMensae(1, 'Pastadag', today),
      generateMensae(2, 'Pizzadag', new Date(today.setDate(today.getDate() + 2))),
      generateMensae(3, 'Soepdag', new Date(today.setDate(today.getDate() - 2))),
    ], {});
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.bulkDelete('mensas', null, {});
  }
};
