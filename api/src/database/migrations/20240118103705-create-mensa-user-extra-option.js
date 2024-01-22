'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('mensa_user_extra_options', {
      mensa_user_id: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
      },
      mensa_extra_option_id: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
      },
    });

    await queryInterface.addConstraint('mensa_user_extra_options', {
      type: 'foreign key',
      fields: ['mensa_user_id'],
      references: {
        table: 'mensa_users',
        field: 'id',
      },
      name: 'mensa_user_extra_options_mensa_user_id_foreign',
    });

    await queryInterface.addConstraint('mensa_user_extra_options', {
      type: 'foreign key',
      fields: ['mensa_extra_option_id'],
      references: {
        table: 'mensa_extra_options',
        field: 'id',
      },
      name: 'mensa_user_extra_options_mensa_extra_option_id_foreign',
    });
  },

  down: async (queryInterface) => {
    await queryInterface.removeConstraint('mensa_user_extra_options', 'mensa_user_extra_options_mensa_user_id_foreign');
    await queryInterface.removeConstraint('mensa_user_extra_options', 'mensa_user_extra_options_mensa_extra_option_id_foreign');
    await queryInterface.dropTable('mensa_user_extra_options');
  }
};