<?php

namespace Drupal\commerce_franchise\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;

/**
 * Class FranchiseLocationCookie.
 */
class FranchiseLocationCookie implements EventSubscriberInterface {

  /**
   * Constructs a new FranchiseLocationCookie object.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['kernel.request'] = ['setLocationId'];

    return $events;
  }

  /**
   * Set Drupal_visitor_store_id cookie value to Store ID.
   */
  public function setLocationId(Event $event) {
    $storefront_id = $event->getRequest()->query->get('storefront_id');
    if ($storefront_id) {
      $storefront = Node::load($storefront_id);
    }

    $node = $event->getRequest()->attributes->get('node');
    if (!empty($node) && $node->bundle() === 'storefront') {
      $storefront = $node;
    }

    if (isset($storefront)) {

      // This works for the location indicator.
      user_cookie_save(['storefrontNid' => $storefront->id()]);
      user_cookie_save(['storefrontName' => $storefront->label()]);
      user_cookie_save(['storefrontUrl' => $storefront->url()]);

      // This works for the footer.
      $_COOKIE['Drupal_visitor_storefrontNid'] = $storefront->id();
      $_COOKIE['Drupal_visitor_storefrontName'] = $storefront->label();
      $_COOKIE['Drupal_visitor_storefrontUrl'] = $storefront->url();


      // Get Commerce Store from Storefront.
      $store = \Drupal::entityTypeManager()
        ->getStorage('commerce_store')
        ->loadByProperties(['field_storefronts' => $storefront->id()]);

      if (!empty($store)) {
        $store = array_pop($store);
        $store_id = $store->id();
        user_cookie_save(['storeId' => $store->id()]);
        $_COOKIE['Drupal_visitor_storeId'] = $store->id();

        $saved_store_id = $event->getRequest()->cookies->get('Drupal_visitor_storeId');
        if (!($store_id === $saved_store_id)) {
          // Delete carts.
          $cart_provider = \Drupal::service('commerce_cart.cart_provider');
          $carts = $cart_provider->getCarts();
          foreach ($carts as $cart) {
            $cart->delete();
          }
        }
      }
    }
  }
}
