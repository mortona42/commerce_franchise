<?php

/**
 * @file
 * Contains franchise_product.page.inc.
 *
 * Page callback for Franchise product entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Franchise product templates.
 *
 * Default template: franchise_product.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_franchise_product(array &$variables) {
  // Fetch FranchiseProduct Entity Object.
  $franchise_product = $variables['elements']['#franchise_product'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
