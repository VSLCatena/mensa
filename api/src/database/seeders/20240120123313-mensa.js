'use strict';
const { faker } = require('@faker-js/faker');

const generateMensae = (id, title, mensaDate, closingHour = 15) => {
  mensaDate.setHours(18, 0, 0, 0) // mensa starts at 18:00
  return {
    id: id,
    title: title,
    date: formatDateTime(mensaDate),
    closing_time: formatDateTime(new Date(mensaDate.setHours(closingHour, 0, 0, 0))),
    max_users: faker.number.int({min: 15, max: 45}),
    price: 4.50,
    closed: isDateInPast(mensaDate),
    created_at: formatDateTime(new Date(mensaDate.getTime() - 7 * 24 * 60 * 60 * 1000)), // 7 days ago
    updated_at: formatDateTime(new Date(mensaDate.getTime() - 7 * 24 * 60 * 60 * 1000)) // 7 days ago
  }
}

function isDateInPast(inputDate) {
  const currentDate = new Date();
  return inputDate < currentDate;
}

const formatDateTime = (date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const seconds = String(date.getSeconds()).padStart(2, '0');
  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};


/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    const today = new Date();
    await queryInterface.bulkInsert('mensas', [
      generateMensae(1, 'Pastadag', today),
      generateMensae(2, 'Pizzadag', new Date(today.getTime() + 2 * 24 * 60 * 60 * 1000)), // 2 days from now
      generateMensae(3, 'Soepdag', new Date(today.getTime() - 2 * 24 * 60 * 60 * 1000)), // 2 days ago
      generateMensae(4, 'Mensa dag', new Date(today.getTime() - 10 * 24 * 60 * 60 * 1000)), // 10 days ago
      generateMensae(5, 'Wijn mensa', new Date(today.getTime() + 10 * 24 * 60 * 60 * 1000)), // 10 days from now
    ], {});
  },

  async down(queryInterface, Sequelize) {
    await queryInterface.bulkDelete('mensas', null, {});
  }
};
