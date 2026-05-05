# WhYn0t373/project-backlog

<!-- Existing README content -->

## Accessibility

This project follows WCAG 2.1 AA guidelines. An automated accessibility audit is performed using **axe-core** and manual keyboard navigation checks.

### Automated Tests

- `npm run test:accessibility` – runs axe-core on all public HTML files.
- The test configuration focuses on WCAG 2.1 AA and includes custom rule settings (e.g., stricter color contrast).

### Manual Checks

- Verify that all interactive elements are reachable via keyboard (`Tab`, `Enter`, `Space`).
- Confirm that screen readers announce meaningful labels for buttons, links, and form controls.
- Check that focus states are visible and that elements follow a logical tab order.

All accessibility tests are integrated into the CI pipeline. No critical or major violations should exist in any commit. If a violation is found, the pipeline will fail until it is addressed.

## Contributing

<!-- Existing contributing guidelines -->