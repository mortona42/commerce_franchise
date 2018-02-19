<?php

namespace Drupal\commerce_franchise;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Franchise product entity.
 *
 * @see \Drupal\commerce_franchise\Entity\FranchiseProduct.
 */
class FranchiseProductAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\commerce_franchise\Entity\FranchiseProductInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished franchise product entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published franchise product entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit franchise product entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete franchise product entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add franchise product entities');
  }

}
