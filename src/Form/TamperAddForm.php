<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\feeds_tamper\Entity\FeedsTamperInterface;
use Drupal\tamper\TamperManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TamperAddForm extends TamperFormBase {

  /**
   * @var \Drupal\tamper\TamperManager
   */
  protected $tamperManager;

  public function __construct(TamperManager $tamper_manager) {
    $this->tamperManager = $tamper_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.tamper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $feeds_tamper = NULL, $source = NULL, $tamper = NULL) {
    $form = parent::buildForm($form, $form_state, $feeds_tamper, $source, $tamper);

    $form['#title'] = $this->t('Add %label tamper', ['%label' => $this->tamper->getPluginId()]);
    $form['actions']['submit']['#value'] = $this->t('Add tamper');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareTamper($tamper) {
    return $this->tamperManager->createInstance($tamper, []);
  }

}
