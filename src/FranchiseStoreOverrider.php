<?php

namespace Drupal\commerce_franchise;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

/**
 * Class FranchiseStoreOverrider.
 */
class FranchiseStoreOverrider implements ConfigFactoryOverrideInterface {

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = [];
    if (in_array('commerce_payment.commerce_payment_gateway.authorize_net', $names)) {

      $store = \Drupal::service('commerce_store.current_store')->getStore();

      if ($store && !$store->field_authnet_api_login->isEmpty() && !$store->field_authnet_transaction_key->isEmpty()) {
        $authnet_api_login = $store->get('field_authnet_api_login')->value;
        $authnet_transaction_key = $store->get('field_authnet_transaction_key')->value;
        $authnet_client_key = $store->get('field_authorize_net_client_key')->value;

        $overrides['commerce_payment.commerce_payment_gateway.authorize_net']['configuration'] = [
          'api_login' => $authnet_api_login,
          'transaction_key' => $authnet_transaction_key,
          'client_key' => $authnet_client_key,
        ];
      }

    }
    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'FranchiseStoreOverrider';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}
