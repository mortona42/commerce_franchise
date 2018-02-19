<?php

namespace Drupal\commerce_franchise\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Franchise product type entity.
 *
 * @ConfigEntityType(
 *   id = "franchise_product_type",
 *   label = @Translation("Franchise product type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\commerce_franchise\FranchiseProductTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_franchise\Form\FranchiseProductTypeForm",
 *       "edit" = "Drupal\commerce_franchise\Form\FranchiseProductTypeForm",
 *       "delete" = "Drupal\commerce_franchise\Form\FranchiseProductTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\commerce_franchise\FranchiseProductTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "franchise_product_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "franchise_product",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/franchise_product_type/{franchise_product_type}",
 *     "add-form" = "/admin/commerce/franchise_product_type/add",
 *     "edit-form" = "/admin/commerce/franchise_product_type/{franchise_product_type}/edit",
 *     "delete-form" = "/admin/commerce/franchise_product_type/{franchise_product_type}/delete",
 *     "collection" = "/admin/commerce/franchise_product_type"
 *   }
 * )
 */
class FranchiseProductType extends ConfigEntityBundleBase implements FranchiseProductTypeInterface {

  /**
   * The Franchise product type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Franchise product type label.
   *
   * @var string
   */
  protected $label;

}
