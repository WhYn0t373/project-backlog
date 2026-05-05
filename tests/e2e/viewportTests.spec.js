/// <reference types="cypress" />

/**
 * Viewport responsiveness tests
 *
 * These tests verify that the application adapts correctly to
 * a set of commonly used viewport widths:
 * - 320px (mobile portrait)
 * - 768px (tablet portrait)
 * - 1024px (desktop)
 *
 * The tests ensure that the rendered page matches the
 * requested viewport size and that no horizontal scrolling
 * occurs, which is a strong indicator that the layout
 * responds to CSS breakpoints.
 */
describe('Viewport responsiveness', () => {
  const baseUrl = '/';
  const viewports = [
    { width: 320, height: 800, label: 'mobile portrait (320×800)' },
    { width: 768, height: 1024, label: 'tablet portrait (768×1024)' },
    { width: 1024, height: 768, label: 'desktop (1024×768)' },
  ];

  viewports.forEach(({ width, height, label }) => {
    it(`renders correctly on ${label}`, () => {
      // Set the viewport and load the page
      cy.viewport(width, height);
      cy.visit(baseUrl);

      // Validate that the viewport matches the requested size
      cy.window().then((win) => {
        expect(win.innerWidth).to.equal(width);
        expect(win.innerHeight).to.equal(height);
      });

      // Validate that the document root is sized correctly
      cy.document().then((doc) => {
        const root = doc.documentElement;
        // clientWidth/Height represent the viewport size
        expect(root.clientWidth).to.equal(width);
        expect(root.clientHeight).to.equal(height);
        // scrollWidth/Height should not exceed viewport (no horizontal scrolling)
        expect(root.scrollWidth).to.be.at.most(width);
        expect(root.scrollHeight).to.be.at.most(height);
      });

      // Basic sanity check: page body should be visible
      cy.get('body').should('be.visible');
    });
  });
});