<?php

namespace Drupal\feeds_tamper\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Feeds tamper entity.
 *
 * @ConfigEntityType(
 *   id = "feeds_tamper",
 *   label = @Translation("Feeds tamper"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\feeds_tamper\Form\FeedsTamperForm",
 *       "edit" = "Drupal\feeds_tamper\Form\FeedsTamperEditForm",
 *       "delete" = "Drupal\feeds_tamper\Form\FeedsTamperDeleteForm"
 *     },
 *     "list_builder" = "Drupal\feeds_tamper\FeedsTamperListBuilder",
 *   },
 *   config_prefix = "feeds_tamper",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/feeds_tamper/{feeds_tamper}",
 *     "add-form" = "/admin/structure/feeds_tamper/add",
 *     "edit-form" = "/admin/structure/feeds_tamper/{feeds_tamper}/edit",
 *     "delete-form" = "/admin/structure/feeds_tamper/{feeds_tamper}/delete",
 *     "collection" = "/admin/structure/feeds_tamper"
 *   },
 *   config_export = {
 *     "id",
 *     "targetFeedType",
 *     "tampers",
 *   }
 * )
 */
class FeedsTamper extends ConfigEntityBase implements FeedsTamperInterface {

  /**
   * The Feeds tamper ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The feed type id.
   *
   * @var string
   */
  protected $targetFeedType;

  /**
   * List of tampers to apply.
   *
   * @var array
   */
  protected $tampers = [];

  public function getTargetFeedTypeId() {
    return 'test_feed'; //$this->targetFeedType;
  }

  public function getTampers($source) {
    return isset($this->tampers[$source]) ? $this->tampers[$source] : [];
  }

  public function addTamper($source, $tamper) {
    if (empty($this->tampers[$source])) {
      $this->tampers[$source] = [
        'source' => $source
      ];
    }
    $this->tampers[$source]['source_tampers'][] = [
      'type' => $tamper->getPluginId(),
      'weight' > $tamper->getWeight(),
      'settings' => $tamper->getConfiguration(),
      'uuid' => 'xxxxxxx',
    ];
  }
}
