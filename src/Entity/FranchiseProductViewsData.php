<?php

namespace Drupal\commerce_franchise\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Franchise product entities.
 */
class FranchiseProductViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
