'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  async up(queryInterface) {
    await queryInterface.createTable('mensa_extra_options', {
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
      description: {
        type: DataTypes.STRING(191),
        allowNull: false,
      },
      price: {
        type: DataTypes.DECIMAL(8, 2),
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

  async down(queryInterface) {
    await queryInterface.removeConstraint('mensa_extra_options', 'mensa_extra_options_mensa_id_foreign');
    await queryInterface.dropTable('mensa_extra_options');
  }
};
