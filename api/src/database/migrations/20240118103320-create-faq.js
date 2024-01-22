'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('faqs', {
      id: {
        type: DataTypes.INTEGER.UNSIGNED,
        autoIncrement: true,
        allowNull: false,
        primaryKey: true,
      },
      question: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      answer: {
        type: DataTypes.TEXT,
        allowNull: false,
      },
      last_edited_by: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      created_at: {
        type: DataTypes.DATE,
        allowNull: false,
      },
      updated_at: {
        type: DataTypes.DATE,
        allowNull: false,
      },
    });

    await queryInterface.addConstraint('faqs', {
      type: 'foreign key',
      fields: ['last_edited_by'],
      references: {
        table: 'users',
        field: 'membership_number', // Adjust this field based on your User model
      },
      name: 'faqs_last_edited_by_foreign',
    });
  },

  down: async (queryInterface) => {
    await queryInterface.removeConstraint('faqs', 'faqs_last_edited_by_foreign');
    await queryInterface.dropTable('faqs');
  }
};
