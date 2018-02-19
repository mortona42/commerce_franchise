<?php

namespace Drupal\commerce_franchise;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Franchise product entities.
 *
 * @ingroup commerce_franchise
 */
class FranchiseProductListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Franchise product ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\commerce_franchise\Entity\FranchiseProduct */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.franchise_product.edit_form',
      ['franchise_product' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
