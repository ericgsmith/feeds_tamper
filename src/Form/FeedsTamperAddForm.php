<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Core\Form\FormStateInterface;

class FeedsTamperAddForm extends FeedsTamperBaseForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $feeds_feed_type = NULL) {
    $this->entity->setTargetFeedTypeId($feeds_feed_type);

    $form['#title'] = $this->t('Add feed tamper mapping for: @feed', ['@feed' => $this->getFeed()->label()]);

    $form['targetFeedTypeId'] = [
      '#type' => 'value',
      '#value' => $feeds_feed_type,
    ];

    $form['id'] = [
      '#type' => 'value',
      '#value' => $feeds_feed_type . '_map',
    ];

    return parent::form($form, $form_state);
  }

}
