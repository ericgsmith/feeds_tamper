<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FeedsTamperForm.
 */
class FeedsTamperForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\feeds_tamper\Entity\FeedsTamper $feeds_tamper */
    $feeds_tamper = $this->entity;

    $form['id'] = [
      '#type' => 'textfield',
      '#title' => 'Id',
      '#default_value' => $feeds_tamper->id(),
    ];

    $form['targetFeedType'] = [
      '#type' => 'textfield',
      '#title' => 'Target',
      '#default_value' => $feeds_tamper->getTargetFeedType()
    ];


    /** @var \Drupal\feeds\FeedTypeInterface $feed */
    $feed = \Drupal::entityTypeManager()->getStorage('feeds_feed_type')->load('test_feed');
    $sources = $feed->getMappingSources();

    $form['tampers'] = [
      '#type' => 'fieldset',
      '#title' => 'Mappings',
      '#tree' => TRUE,
    ];

    foreach ($sources as $source) {
      $sourceFieldset = [
        '#type' => 'fieldset',
        '#title' => $source['label'],
        '#tree' => TRUE,
      ];
      $sourceFieldset['source'] = [
        '#type' => 'hidden',
        '#value' => $source['value'],
      ];
      $sourceFieldset['source_tampers'] = [
        '#type' => 'container',
        '#tree' => TRUE,
      ];

      $sourceFieldset['source_tampers'][] = [
        'type' => ['#type' => 'textfield'],
        'weight' => ['#type' => 'textfield'],
        //'settings' => ['#type' => 'textfield']
      ];

      $form['tampers'][] = $sourceFieldset;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $feeds_tamper = $this->entity;
    $status = $feeds_tamper->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Feeds tamper.', [
          '%label' => $feeds_tamper->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Feeds tamper.', [
          '%label' => $feeds_tamper->label(),
        ]));
    }
    $form_state->setRedirectUrl($feeds_tamper->toUrl('collection'));
  }

}
