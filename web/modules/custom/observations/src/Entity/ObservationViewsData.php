<?php

/**
 * @file
 * Contains \Drupal\observations\Entity\Observation.
 */

namespace Drupal\observations\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Observation entities.
 */
class ObservationViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['observation']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Observation'),
      'help' => $this->t('The Observation ID.'),
    );

    return $data;
  }

}
