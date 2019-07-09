<?php

/**
 * @file
 * Contains \Drupal\observations\Entity\ObservationViewBuilder.
 */

namespace Drupal\observations\Entity;

use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides presentation of Observation entities.
 */
class ObservationViewBuilder extends EntityViewBuilder {
  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $entity, $view_mode = 'full', $langcode = NULL) {
    $build[] = parent::view($entity, $view_mode, $langcode);
    $build['#theme_wrappers'] = [
      'container' => [
        '#attributes' => [
          'class' => ['observation-full-view']
        ],
      ],
    ];
    
    return $build;
  }

}
