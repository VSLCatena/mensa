'use strict';

const { DataTypes } = require('sequelize');

/**
 * @type {import('sequelize-cli').Migration}
 */
module.exports = {
  async up(queryInterface) {
    await queryInterface.createTable('mensa_user_extra_options', {
      mensaUserId: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
        field: 'mensa_user_id',
        references: {
          model: 'mensa_users',
          key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'CASCADE',
      },
      mensaExtraOptionId: {
        type: DataTypes.INTEGER.UNSIGNED,
        allowNull: false,
        field: 'mensa_extra_option_id',
        references: {
          model: 'mensa_extra_options',
          key: 'id',
        },
        onUpdate: 'CASCADE',
        onDelete: 'CASCADE',
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
      onDelete: 'CASCADE',
    });

    await queryInterface.addConstraint('mensa_user_extra_options', {
      type: 'foreign key',
      fields: ['mensa_extra_option_id'],
      references: {
        table: 'mensa_extra_options',
        field: 'id',
      },
      name: 'mensa_user_extra_options_mensa_extra_option_id_foreign',
      onDelete: 'CASCADE',
    });
  },

  async down(queryInterface) {
    await queryInterface.removeConstraint('mensa_user_extra_options', 'mensa_user_extra_options_mensa_user_id_foreign');
    await queryInterface.removeConstraint('mensa_user_extra_options', 'mensa_user_extra_options_mensa_extra_option_id_foreign');
    await queryInterface.dropTable('mensa_user_extra_options');
  }
};
