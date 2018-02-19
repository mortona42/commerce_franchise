<?php

namespace Drupal\commerce_franchise\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Franchise product entity.
 *
 * @ingroup commerce_franchise
 *
 * @ContentEntityType(
 *   id = "franchise_product",
 *   label = @Translation("Franchise product"),
 *   bundle_label = @Translation("Franchise product type"),
 *   handlers = {
 *     "storage" = "Drupal\commerce_franchise\FranchiseProductStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\commerce_franchise\FranchiseProductListBuilder",
 *     "views_data" = "Drupal\commerce_franchise\Entity\FranchiseProductViewsData",
 *     "translation" = "Drupal\commerce_franchise\FranchiseProductTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\commerce_franchise\Form\FranchiseProductForm",
 *       "add" = "Drupal\commerce_franchise\Form\FranchiseProductForm",
 *       "edit" = "Drupal\commerce_franchise\Form\FranchiseProductForm",
 *       "delete" = "Drupal\commerce_franchise\Form\FranchiseProductDeleteForm",
 *     },
 *     "access" = "Drupal\commerce_franchise\FranchiseProductAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\commerce_franchise\FranchiseProductHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "franchise_product",
 *   data_table = "franchise_product_field_data",
 *   revision_table = "franchise_product_revision",
 *   revision_data_table = "franchise_product_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer franchise product entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/franchise_product/{franchise_product}",
 *     "add-page" = "/admin/commerce/franchise_product/add",
 *     "add-form" = "/admin/commerce/franchise_product/add/{franchise_product_type}",
 *     "edit-form" = "/admin/commerce/franchise_product/{franchise_product}/edit",
 *     "delete-form" = "/admin/commerce/franchise_product/{franchise_product}/delete",
 *     "version-history" = "/admin/commerce/franchise_product/{franchise_product}/revisions",
 *     "revision" = "/admin/commerce/franchise_product/{franchise_product}/revisions/{franchise_product_revision}/view",
 *     "revision_revert" = "/admin/commerce/franchise_product/{franchise_product}/revisions/{franchise_product_revision}/revert",
 *     "revision_delete" = "/admin/commerce/franchise_product/{franchise_product}/revisions/{franchise_product_revision}/delete",
 *     "translation_revert" = "/admin/commerce/franchise_product/{franchise_product}/revisions/{franchise_product_revision}/revert/{langcode}",
 *     "collection" = "/admin/commerce/franchise_product",
 *   },
 *   bundle_entity_type = "franchise_product_type",
 *   field_ui_base_route = "entity.franchise_product_type.edit_form"
 * )
 */
class FranchiseProduct extends RevisionableContentEntityBase implements FranchiseProductInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the franchise_product owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Franchise product entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Franchise product entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Franchise product is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['price'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Price'))
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'type' => 'float',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['stores'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Stores'))
      ->setDescription(t('description'))
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setRequired(TRUE)
      ->setSetting('target_type', 'commerce_store')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'commerce_entity_select',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);;

    return $fields;
  }

}
