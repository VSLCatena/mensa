'use strict';
require('dotenv').config();

module.exports = {
  database: process.env.DB_DATABASE   ||  'mydatabase',
  username: process.env.DB_USERNAME ||  'user',
  password: process.env.DB_PASSWORD   ||  pwd,
  host:     '127.0.0.1',
  port:     process.env.DB_PORT || 3306,
  dialect: 'mariadb'
};