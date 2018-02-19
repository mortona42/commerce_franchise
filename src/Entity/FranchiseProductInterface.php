<?php

namespace Drupal\commerce_franchise\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Franchise product entities.
 *
 * @ingroup commerce_franchise
 */
interface FranchiseProductInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Franchise product name.
   *
   * @return string
   *   Name of the Franchise product.
   */
  public function getName();

  /**
   * Sets the Franchise product name.
   *
   * @param string $name
   *   The Franchise product name.
   *
   * @return \Drupal\commerce_franchise\Entity\FranchiseProductInterface
   *   The called Franchise product entity.
   */
  public function setName($name);

  /**
   * Gets the Franchise product creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Franchise product.
   */
  public function getCreatedTime();

  /**
   * Sets the Franchise product creation timestamp.
   *
   * @param int $timestamp
   *   The Franchise product creation timestamp.
   *
   * @return \Drupal\commerce_franchise\Entity\FranchiseProductInterface
   *   The called Franchise product entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Franchise product published status indicator.
   *
   * Unpublished Franchise product are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Franchise product is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Franchise product.
   *
   * @param bool $published
   *   TRUE to set this Franchise product to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\commerce_franchise\Entity\FranchiseProductInterface
   *   The called Franchise product entity.
   */
  public function setPublished($published);

  /**
   * Gets the Franchise product revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Franchise product revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\commerce_franchise\Entity\FranchiseProductInterface
   *   The called Franchise product entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Franchise product revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Franchise product revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\commerce_franchise\Entity\FranchiseProductInterface
   *   The called Franchise product entity.
   */
  public function setRevisionUserId($uid);

}
