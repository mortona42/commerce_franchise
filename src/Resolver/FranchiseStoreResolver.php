<?php

namespace Drupal\commerce_franchise\Resolver;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_store\Resolver\StoreResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_store\Entity\Store;

/**
 * Gets the store from user cookie.
 */
class FranchiseStoreResolver implements StoreResolverInterface {

  /**
   * The store storage.
   *
   * @var \Drupal\commerce_store\StoreStorageInterface
   */
  protected $storage;

  /**
   * Constructs a new FranchiseStoreResolver object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->storage = $entity_type_manager->getStorage('commerce_store');
  }

  /**
   * {@inheritdoc}
   */
  public function resolve() {
    $store_id = Request::createFromGlobals()->cookies->get('Drupal_visitor_storeId');

    if ($store_id) {
      $store = Store::load($store_id);
      return $store;
    }
  }

}
