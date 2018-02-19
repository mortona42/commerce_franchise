<?php

namespace Drupal\commerce_franchise;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\commerce_franchise\Entity\FranchiseProductInterface;

/**
 * Defines the storage handler class for Franchise product entities.
 *
 * This extends the base storage class, adding required special handling for
 * Franchise product entities.
 *
 * @ingroup commerce_franchise
 */
class FranchiseProductStorage extends SqlContentEntityStorage implements FranchiseProductStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(FranchiseProductInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {franchise_product_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {franchise_product_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(FranchiseProductInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {franchise_product_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('franchise_product_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
