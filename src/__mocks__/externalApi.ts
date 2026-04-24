/**
 * Mock implementation for the external API used by the conversion service.
 * This module will be automatically used by Jest when the real module
 * is imported in the test environment.
 */
export const getRate = jest.fn().mockResolvedValue(1.5);