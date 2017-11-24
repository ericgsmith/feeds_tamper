<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\tamper\TamperManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FeedsTamperEditForm
 */
class FeedsTamperEditForm extends FeedsTamperBaseForm {

  /**
   * @var \Drupal\tamper\TamperManager
   */
  protected $tamperManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityStorageInterface $feed_storage, TamperManager $tamper_manager) {
    parent::__construct($feed_storage);
    $this->tamperManager = $tamper_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('feeds_feed_type'),
      $container->get('plugin.manager.tamper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form['#title'] = $this->t('Tamper settings for feed: @feed', ['@feed' => $this->getFeed()->label()]);
    $form['#tree'] = TRUE;

    $sources = $this->getFeed()->getMappingSources();
    $form['tampers'] = [
      '#type' => 'container',
    ];

    foreach ($sources as $key => $source) {
      $form['tampers'][$key] = [
        '#type' => 'fieldset',
      ];
      $form['tampers'][$key] = $this->getSourceTable($form['tampers'][$key], $form_state, $source);
    }

    return parent::form($form, $form_state);
  }

  protected function getSourceTable(array &$form, FormStateInterface $form_state, $source) {
    $user_input = $form_state->getUserInput();
    $form['#title'] = $source['label'];
    $form['#description'] = $this->t('Tamper plugins for @label', ['@label' => $source['label']]);

    $form['source'] = [
      '#type' => 'value',
      '#value' => $source['value'],
    ];
    $form['source_tampers'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Tamper'),
        $this->t('Weight'),
        $this->t('Operations'),
      ],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'tamper-' . $source['value'] .'-order-weight',
        ],
      ],
      '#attributes' => [
        'id' => 'feed-' . $source['value'] .'-tampers',
      ],
      '#empty' => t('There are currently no tamper plugins for this source. Add one by selecting an option below.'),
      '#weight' => 5,
    ];

    foreach ($this->entity->getTampers($source['value']) as $tamper) {
      // add options
    }

    $new_tamper_options = [];
    $all_tampers = $this->tamperManager->getSortedDefinitions();
    foreach ($all_tampers as $tamper) {
      $new_tamper_options[$tamper['id']] = $tamper['label'];
    }

    $form['source_tampers']['new'] = [
      '#tree' => FALSE,
      '#weight' => isset($user_input['weight']) ? $user_input['weight'] : NULL,
      '#attributes' => ['class' => ['draggable']],
    ];

    $form['source_tampers']['new']['effect'] = [
      'data' => [
        'new' => [
          '#type' => 'select',
          '#title' => $this->t('Tamper'),
          '#title_display' => 'invisible',
          '#options' => $new_tamper_options,
          '#empty_option' => $this->t('Select a new tamper'),
        ],
        [
          'add' => [
            '#type' => 'submit',
            '#value' => $this->t('Add'),
            '#validate' => ['::tamperValidate'],
            '#submit' => ['::submitForm', '::tamperSave'],
          ],
        ],
      ],
      '#prefix' => '<div class="tamper-new">',
      '#suffix' => '</div>',
    ];

    $form['source_tampers']['new']['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight for new effect'),
      '#title_display' => 'invisible',
      '#default_value' => count($this->entity->getTampers($source['value'])) + 1,
      '#attributes' => ['class' => ['image-effect-order-weight']],
    ];
    $form['source_tampers']['new']['operations'] = [
      'data' => [],
    ];

    return $form;
  }

  public function tamperValidate($form, FormStateInterface $form_state) {

  }

  /**
   * Submit handler for image effect.
   */
  public function tamperSave($form, FormStateInterface $form_state) {
    $this->save($form, $form_state);

    $tamper = $this->tamperManager->getDefinition($form_state->getValue('new'));

    if (is_subclass_of($tamper['class'], 'X')) { // @todo: Add config interface.
      // add redirect to add form.
    }
    else {
      // create redirect and save to feed tamper.
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // update weights...
  }

}
