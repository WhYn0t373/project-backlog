/**
 * Jest configuration for the project.
 *
 * - Uses the Node test environment.
 * - Executes `test/setup.js` after Jest has been initialized to configure
 *   global settings and environment variables.
 * - Sets a generous test timeout to accommodate database operations.
 */

module.exports = {
  testEnvironment: 'node',
  setupFilesAfterEnv: ['<rootDir>/test/setup.js'],
  testTimeout: 30_000, // 30 seconds
};