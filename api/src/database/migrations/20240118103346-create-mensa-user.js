'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  async up(queryInterface) {
    await queryInterface.createTable('mensa_users', {
      id: {
        type: DataTypes.INTEGER.UNSIGNED,
        autoIncrement: true,
        allowNull: false,
        primaryKey: true,
      },
      membershipNumber: {
        type: DataTypes.STRING(191),
        allowNull: false,
        references: {
          model: 'users',
          key: 'lidnummer',
        },
      },
      mensaId: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
        references: {
          model: 'mensas',
          key: 'id',
        },
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
      isIntro: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
      },
      allergies: {
        type: DataTypes.STRING(191),
        defaultValue: null,
      },
      extraInfo: {
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
        defaultValue: 0.00,
      },
      createdAt: {
        type: DataTypes.DATE,
      },
      updatedAt: {
        type: DataTypes.DATE,
      },
      deletedAt: {
        type: DataTypes.DATE,
      },
      confirmationCode: {
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
      fields: ['lidnummer'],
      references: {
        table: 'users',
        field: 'lidnummer',
      },
      name: 'mensa_users_lidnummer_foreign',
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

  async down(queryInterface) {
    await queryInterface.removeConstraint('mensa_users', 'mensa_users_lidnummer_foreign');
    await queryInterface.removeConstraint('mensa_users', 'mensa_users_mensa_id_foreign');
    await queryInterface.dropTable('mensa_users');
  }
};
