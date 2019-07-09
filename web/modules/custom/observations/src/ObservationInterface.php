<?php

/**
 * @file
 * Contains \Drupal\observations\ObservationInterface.
 */

namespace Drupal\observations;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Observation entities.
 *
 * @ingroup observations
 */
interface ObservationInterface extends ContentEntityInterface, EntityChangedInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Observation creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Observation.
   */
  public function getCreatedTime();

  /**
   * Sets the Observation creation timestamp.
   *
   * @param int $timestamp
   *   The Observation creation timestamp.
   *
   * @return \Drupal\observations\ObservationInterface
   *   The called Observation entity.
   */
  public function setCreatedTime($timestamp);

}
