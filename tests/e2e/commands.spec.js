/// <reference types="cypress" />

/**
 * Tests for the custom Cypress command `visitWithViewport`.
 * These tests confirm that the command correctly sets the
 * viewport size and navigates to the requested URL.
 */
describe('visitWithViewport custom command', () => {
  it('sets viewport and visits the specified URL', () => {
    const width = 500;
    const height = 600;
    cy.visitWithViewport('/', width, height);

    cy.window().then((win) => {
      expect(win.innerWidth).to.equal(width);
      expect(win.innerHeight).to.equal(height);
    });

    cy.location('pathname').should('eq', '/');
  });

  it('defaults the height to 800 when not provided', () => {
    const width = 400;
    cy.visitWithViewport('/', width);

    cy.window().then((win) => {
      expect(win.innerWidth).to.equal(width);
      expect(win.innerHeight).to.equal(800);
    });

    cy.location('pathname').should('eq', '/');
  });
});