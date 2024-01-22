'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('mensa_users', {
      id: {
        type: DataTypes.INTEGER.UNSIGNED,
        autoIncrement: true,
        allowNull: false,
        primaryKey: true,
      },
      membership_number: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      mensa_id: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
      },
      cooks: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      dishwasher: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      is_intro: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      allergies: {
        type: DataTypes.STRING(191),
        defaultValue: null,
      },
      extra_info: {
        type: DataTypes.STRING(191),
        defaultValue: null,
      },
      confirmed: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      paid: {
        type: DataTypes.DECIMAL(8, 2),
        allowNull: false,
        defaultValue: 0.0,
      },
      created_at: {
        type: DataTypes.DATE,
        allowNull: false,
      },
      updated_at: {
        type: DataTypes.DATE,
        allowNull: false,
      },
      deleted_at: {
        type: DataTypes.DATE,
      },
      confirmation_code: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      vegetarian: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
    });

    await queryInterface.addConstraint('mensa_users', {
      type: 'foreign key',
      fields: ['membership_number'],
      references: {
        table: 'users',
        field: 'membership_number',
      },
      name: 'mensa_users_membership_number_foreign',
    });

    await queryInterface.addConstraint('mensa_users', {
      type: 'foreign key',
      fields: ['mensa_id'],
      references: {
        table: 'mensas',
        field: 'id',
      },
      name: 'mensa_users_mensa_id_foreign',
    });
  },

  down: async (queryInterface) => {
    await queryInterface.removeConstraint('mensa_users', 'mensa_users_membership_number_foreign');
    await queryInterface.removeConstraint('mensa_users', 'mensa_users_mensa_id_foreign');
    await queryInterface.dropTable('mensa_users');
  }
};