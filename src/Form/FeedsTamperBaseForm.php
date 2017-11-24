<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FeedsTamperForm.
 */
class FeedsTamperBaseForm extends EntityForm {

  /**
   * @var \Drupal\feeds_tamper\Entity\FeedsTamperInterface
   */
  protected $entity;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $feedStorge;

  /**
   * Constructs a base class for image style add and edit forms.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $feed_storage
   *   The image style entity storage.
   */
  public function __construct(EntityStorageInterface $feed_storage) {
    $this->feedStorge = $feed_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('feeds_feed_type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $form_state->setRedirectUrl($this->entity->toUrl('edit-form'));
  }

  /**
   * Get the feed.
   *
   * @return \Drupal\feeds\FeedTypeInterface|null
   *   The feed entity.
   */
  protected function getFeed() {
    return $this->entity->getTargetFeedTypeId()  ?
      $this->feedStorge->load($this->entity->getTargetFeedTypeId()) : null;
  }

}
