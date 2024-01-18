'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  async up(queryInterface) {
    await queryInterface.createTable('menu_items', {
      id: {
        type: DataTypes.INTEGER.UNSIGNED,
        autoIncrement: true,
        allowNull: false,
        primaryKey: true,
      },
      mensaId: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
        references: {
          model: 'mensas',
          key: 'id',
        },
      },
      order: {
        type: DataTypes.SMALLINT,
        allowNull: false,
      },
      text: {
        type: DataTypes.STRING(191),
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

  async down(queryInterface) {
    await queryInterface.removeConstraint('menu_items', 'menu_items_mensa_id_foreign');
    await queryInterface.dropTable('menu_items');
  }
};
