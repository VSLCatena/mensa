'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('users', {
      membership_number: {
        type: DataTypes.STRING(191),
        allowNull: false,
        primaryKey: true,
      },
      name: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      email: {
        type: DataTypes.STRING(191),
      },
      allergies: {
        type: DataTypes.STRING(191),
      },
      extra_info: {
        type: DataTypes.STRING(191),
      },
      mensa_admin: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      remember_token: {
        type: DataTypes.STRING(100),
      },
      phone_number: {
        type: DataTypes.STRING(191),
      },
      vegetarian: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      service_user: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
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
  },

  down: async (queryInterface) => {
    await queryInterface.dropTable('users');
  }
};

