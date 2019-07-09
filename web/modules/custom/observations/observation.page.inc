<?php

/**
 * @file
 * Contains observation.page.inc..
 *
 * Page callback for Observation entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Observation templates.
 *
 * Default template: observation.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_observation(array &$variables) {
  // Fetch Observation Entity Object.
  $observation = $variables['elements']['#observation'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
  
}
