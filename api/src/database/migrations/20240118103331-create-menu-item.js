'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('menu_items', {
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
      order: {
        type: DataTypes.SMALLINT,
        allowNull: false,
      },
      text: {
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

    await queryInterface.addConstraint('menu_items', {
      type: 'foreign key',
      fields: ['mensa_id'],
      references: {
        table: 'mensas',
        field: 'id',
      },
      name: 'menu_items_mensa_id_foreign',
    });
  },

  down: async (queryInterface) => {
    await queryInterface.removeConstraint('menu_items', 'menu_items_mensa_id_foreign');
    await queryInterface.dropTable('menu_items');
  }
};
