/** @type {import('ts-jest').JestConfigWithTsJest} */
module.exports = {
  verbose: true,
  collectCoverage: true,
  collectCoverageFrom: ['resources/assets/**/*.ts',],
  preset: 'ts-jest',
  testEnvironment: 'node',
  testMatch: ['**/tests/frontend/**/*.test.ts'],
};