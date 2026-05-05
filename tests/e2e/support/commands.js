/// <reference types="cypress" />

/**
 * Custom Cypress commands
 *
 * This file contains reusable commands that can be
 * imported into any test file. The current project
 * requires a lightweight command to visit a page
 * at a specific viewport size.
 */

/**
 * Visit a URL while setting the viewport.
 *
 * @param {string} url      - The relative URL to visit.
 * @param {number} width    - The viewport width in pixels.
 * @param {number} [height] - The viewport height in pixels (default 800).
 */
Cypress.Commands.add('visitWithViewport', (url, width, height = 800) => {
  cy.viewport(width, height);
  cy.visit(url);
});