<?php

namespace Drupal\commerce_franchise\EventSubscriber;

use Drupal\iconnect\Entity\iConnectProduct;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;

/**
 * Class FranchiseProductStoreVisibility.
 */
class FranchiseProductStoreVisibility implements EventSubscriberInterface {

  /**
   * Constructs a new FranchiseProductStoreVisibility object.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['commerce_product.commerce_product.presave'] = ['setStoreVisibility'];

    return $events;
  }

  /**
   * Set Drupal_visitor_store_id cookie value to Store ID.
   */
  public function setStoreVisibility(Event $event) {
    $product = $event->getProduct();
    $variations = $product->getVariations();
    $skus = [];

    foreach ($variations as $variation) {
      $skus[] = $variation->getSku();
    }

    $iconnect_products = db_select('iconnect_product', 'icp')
      ->distinct()
      ->condition('sku', $skus, 'IN')
      ->condition('status', 1);
    $iconnect_products->addJoin('INNER', 'iconnect_product__field_store_connection', 'icp_store', 'icp.id = icp_store.entity_id');
    $iconnect_products->addField('icp_store', 'field_store_connection_target_id');

    $commerce_stores = $iconnect_products->execute()->fetchCol();

    // TODO: load default store.
    $commerce_stores[] = 1;

    $product->set('stores', $commerce_stores);
  }

}
