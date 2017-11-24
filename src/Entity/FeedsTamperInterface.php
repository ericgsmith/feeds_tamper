<?php

namespace Drupal\feeds_tamper\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Feeds tamper entities.
 */
interface FeedsTamperInterface extends ConfigEntityInterface {

  /**
   * Get the target feed type id.
   *
   * @return string
   */
  public function getTargetFeedTypeId();

  /**
   * Set the target feed type id.
   *
   * @return string
   */
  public function setTargetFeedTypeId($targetFeedTypeId);

  /**
   * Returns a specific tamper plugin.
   *
   * @param string $tamper_uuid
   *   The plugin UUID.
   *
   * @return \Drupal\tamper\TamperInterface
   *   The tamper object.
   */
  public function getTamper($tamper_uuid);

  /**
   * Get the tamper plugins for a source plugin.
   *
   * @param string $source
   *   Source field.
   *
   * @return \Drupal\tamper\TamperInterface[]
   *   List of tampers
   *
   * @todo: Should this be a plugin collection?
   */
  public function getTampers($source);

  /**
   * Add a tamper plugin to the feed.
   *
   * @param string $source
   *   Source field id.
   * @param array $configuration
   *   Configuration of the plugin.
   */
  public function addTamper($source, $configuration);

  /**
   * Delete a tamper plugin.
   *
   * @param string $tamper_uuid
   *   The plugin UUID.
   *
   * @todo: Not sure what we will pass in here.
   */
  public function deleteTamper($tamper_uuid);
}
