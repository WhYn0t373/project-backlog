/* eslint-disable import/no-extraneous-dependencies */
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  // Global timeout for all tests
  timeout: 60000,

  // Number of retries on CI
  retries: 2,

  // Reporter configuration
  reporter: [['list'], ['json', { outputFile: 'test-results.json' }]],

  // Global options applied to all projects
  use: {
    baseURL: 'http://localhost:3000',
    headless: true,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
  },

  // Define projects for BrowserStack
  projects: [
    {
      name: 'chromium',
      use: {
        browserName: 'chromium',
        // BrowserStack capabilities
        'browserstack.user': process.env.BROWSERSTACK_USERNAME,
        'browserstack.key': process.env.BROWSERSTACK_ACCESS_KEY,
        'browserstack.browser': 'chrome',
        'browserstack.browser_version': 'latest',
        'browserstack.os': 'Windows 10',
        'browserstack.resolution': '1920x1080',
        'browserstack.projectName': 'Playwright Tests',
        'browserstack.buildName': `CI Build ${process.env.GITHUB_RUN_ID || 'local'}`,
        'browserstack.sessionName': 'Playwright Test Session',
      },
    },
    {
      name: 'firefox',
      use: {
        browserName: 'firefox',
        'browserstack.user': process.env.BROWSERSTACK_USERNAME,
        'browserstack.key': process.env.BROWSERSTACK_ACCESS_KEY,
        'browserstack.browser': 'firefox',
        'browserstack.browser_version': 'latest',
        'browserstack.os': 'Windows 10',
        'browserstack.resolution': '1920x1080',
        'browserstack.projectName': 'Playwright Tests',
        'browserstack.buildName': `CI Build ${process.env.GITHUB_RUN_ID || 'local'}`,
        'browserstack.sessionName': 'Playwright Test Session',
      },
    },
    {
      name: 'webkit',
      use: {
        browserName: 'webkit',
        'browserstack.user': process.env.BROWSERSTACK_USERNAME,
        'browserstack.key': process.env.BROWSERSTACK_ACCESS_KEY,
        'browserstack.browser': 'safari',
        'browserstack.browser_version': 'latest',
        'browserstack.os': 'macOS Big Sur',
        'browserstack.resolution': '1920x1080',
        'browserstack.projectName': 'Playwright Tests',
        'browserstack.buildName': `CI Build ${process.env.GITHUB_RUN_ID || 'local'}`,
        'browserstack.sessionName': 'Playwright Test Session',
      },
    },
  ],
});