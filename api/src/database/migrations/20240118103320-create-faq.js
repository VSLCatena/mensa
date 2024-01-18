'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  async up(queryInterface) {
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
      lastEditedBy: {
        type: DataTypes.STRING(191),
        allowNull: false,
        references: {
          model: 'users',
          key: 'lidnummer',
        },
      },
      createdAt: {
        type: DataTypes.DATE,
      },
      updatedAt: {
        type: DataTypes.DATE,
      },
    });

    await queryInterface.addConstraint('faqs', {
      type: 'foreign key',
      fields: ['last_edited_by'],
      references: {
        table: 'users',
        field: 'lidnummer',
      },
      name: 'faqs_last_edited_by_foreign',
    });
  },

  async down(queryInterface) {
    await queryInterface.removeConstraint('faqs', 'faqs_last_edited_by_foreign');
    await queryInterface.dropTable('faqs');
  }
};