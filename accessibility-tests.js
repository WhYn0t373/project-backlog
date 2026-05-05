/**
 * Accessibility test runner using axe-core and jsdom.
 * Scans all HTML files in the public directory and reports violations.
 */
const fs = require('fs');
const path = require('path');
const glob = require('glob');
const { JSDOM } = require('jsdom');
const axe = require('axe-core');
const axeConfig = require('./axe-core-config');

const logViolations = (violations, file) => {
  console.error(`\nAccessibility violations in ${file}:\n`);
  violations.forEach(v => {
    console.error(`- ${v.help} (${v.id})`);
    v.nodes.forEach(node => {
      console.error(`  • Target: ${node.target.join(', ')}`);
    });
  });
};

const runAxeOnHtml = async (htmlContent, file) => {
  const dom = new JSDOM(htmlContent, {
    runScripts: 'outside-only',
    resources: 'usable'
  });

  // Wait for any external resources (scripts, styles) to load
  await new Promise(resolve => dom.window.addEventListener('load', resolve));

  const results = await axe.run(dom.window, axeConfig);
  if (results.violations.length) {
    logViolations(results.violations, file);
    return false;
  }
  return true;
};

(async () => {
  const htmlFiles = glob.sync('public/**/*.html');
  if (!htmlFiles.length) {
    console.warn('No HTML files found in public/. Skipping accessibility tests.');
    process.exit(0);
  }

  let allPassed = true;
  for (const file of htmlFiles) {
    const html = fs.readFileSync(file, 'utf8');
    const passed = await runAxeOnHtml(html, file);
    if (!passed) allPassed = false;
  }

  if (!allPassed) {
    console.error('\nAccessibility test failures detected. Please fix the violations.');
    process.exit(1);
  }

  console.log('\n✅ All scanned HTML files passed accessibility tests.');
})();