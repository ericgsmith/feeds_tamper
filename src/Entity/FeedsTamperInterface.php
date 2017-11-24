<?php

namespace Drupal\feeds_tamper\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Feeds tamper entities.
 */
interface FeedsTamperInterface extends ConfigEntityInterface {

  /**
   * Get the target feed type id.
   * @return string
   */
  public function getTargetFeedTypeId();

  /**
   * Get the tamper plugins for a source plugin.
   *
   * @param string $source
   *   Source field.
   *
   * @return \Drupal\tamper\TamperInterface[]
   *   List of tampers
   */
  public function getTampers($source);
}
