<?php

namespace Drupal\commerce_franchise;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface FranchiseProductStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Franchise product revision IDs for a specific Franchise product.
   *
   * @param \Drupal\commerce_franchise\Entity\FranchiseProductInterface $entity
   *   The Franchise product entity.
   *
   * @return int[]
   *   Franchise product revision IDs (in ascending order).
   */
  public function revisionIds(FranchiseProductInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Franchise product author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Franchise product revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\commerce_franchise\Entity\FranchiseProductInterface $entity
   *   The Franchise product entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FranchiseProductInterface $entity);

  /**
   * Unsets the language for all Franchise product with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
