'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('mensa_extra_options', {
      id: {
        type: DataTypes.INTEGER.UNSIGNED,
        autoIncrement: true,
        allowNull: false,
        primaryKey: true,
      },
      mensa_id: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
      },
      description: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      price: {
        type: DataTypes.DECIMAL(8, 2),
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

    await queryInterface.addConstraint('mensa_extra_options', {
      type: 'foreign key',
      fields: ['mensa_id'],
      references: {
        table: 'mensas',
        field: 'id',
      },
      name: 'mensa_extra_options_mensa_id_foreign',
    });
  },

  down: async (queryInterface) => {
    await queryInterface.removeConstraint('mensa_extra_options', 'mensa_extra_options_mensa_id_foreign');
    await queryInterface.dropTable('mensa_extra_options');
  }
};
