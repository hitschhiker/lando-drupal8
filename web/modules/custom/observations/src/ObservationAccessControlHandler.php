<?php

/**
 * @file
 * Contains \Drupal\observations\ObservationAccessControlHandler.
 */

namespace Drupal\observations;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Observation entity.
 *
 * @see \Drupal\observations\Entity\Observation.
 */
class ObservationAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\observations\ObservationInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view observation entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit observation entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete observation entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add observation entities');
  }

}
