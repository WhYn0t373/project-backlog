/**
 * axe-core configuration for accessibility testing.
 * This config focuses on WCAG 2.1 AA compliance and customizes certain rules.
 */
module.exports = {
  runOnly: {
    type: 'tag',
    values: ['wcag2a', 'wcag2aa']
  },
  // Custom rule settings (e.g., tightening color contrast threshold)
  rules: {
    'color-contrast': {
      enabled: true,
      options: {
        // Minimum contrast ratio set to 4.5:1 (WCAG AA for normal text)
        minContrastRatio: 4.5
      }
    },
    // Disable some false‑positive rules if the project uses custom styles
    'link-name': { enabled: false }
  },
  // Set the threshold for acceptable violations count
  threshold: {
    violations: 0,
    incomplete: 0,
    passes: 0
  }
};