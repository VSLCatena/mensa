'use strict';
const { faker } = require('@faker-js/faker');

const generateFaqs = () => {
  const faqs = [];

  for (let i = 1; i <= 10; i++) {
    const faq = {
      question: faker.lorem.sentence(),
      answer: faker.lorem.paragraph(),
      last_edited_by: faker.number.int({min: 400000, max: 400100}),
      created_at: new Date(),
      updated_at: new Date(),
    };

    faqs.push(faq);
  }

  return faqs;
};

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.bulkInsert('faqs', [
      ...generateFaqs(),
    ], {});
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.bulkDelete('faqs', null, {});
  }
};
