import { test, expect } from '@playwright/test';
import fs from 'fs';

/**
 * Minimal cross‑browser compatibility test.
 *
 * This test navigates to the home page, takes a screenshot, and verifies that
 * key UI elements are present. The screenshot is stored under
 * `public/qa-screenshots/<browser>/home.png`.
 */
test('home page renders correctly', async ({ page }) => {
  // Navigate to the root of the site
  await page.goto('/');

  // Verify the page title contains the expected app name
  await expect(page).toHaveTitle(/MyApp/);

  // Verify the main header is visible
  const header = page.locator('header');
  await expect(header).toBeVisible();

  // Verify the navigation menu exists
  const nav = page.locator('nav');
  await expect(nav).toBeVisible();

  // Ensure screenshot directory exists
  const browserName = page.context().browser().name();
  const dir = `public/qa-screenshots/${browserName}`;
  fs.mkdirSync(dir, { recursive: true });

  // Capture a screenshot for visual regression
  await page.screenshot({
    path: `${dir}/home.png`,
    fullPage: true,
  });
});