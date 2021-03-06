<?php

namespace Drupal\feeds_tamper\Form;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\feeds\Entity\FeedType;
use Drupal\feeds_tamper\Entity\FeedsTamper;
use Drupal\feeds_tamper\Entity\FeedsTamperInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class TamperFormBase extends FormBase {

  /**
   * The tamper mapping entity.
   *
   * @var \Drupal\feeds_tamper\Entity\FeedsTamper
   */
  protected $feedsTamper;

  /**
   * The source field the tamper is applied to.
   *
   * @var string
   */
  protected $source;

  /**
   * The tamper plugin.
   *
   * @var \Drupal\tamper\ConfigurableTamperInterface
   */
  protected $tamper;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tamper_form';
  }

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\feeds_tamper\Entity\FeedsTamperInterface $feeds_tamper
   *   Feed tamper object.
   * @param string $source
   *   Source field id.
   * @param string $tamper
   *   Tamper id.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $feeds_tamper = NULL, $source = NULL, $tamper = NULL) {
    $this->feedsTamper = FeedsTamper::load($feeds_tamper);
    $this->source = $source;

    try {
      $this->tamper = $this->prepareTamper($tamper);
    }
    catch (PluginNotFoundException $e) {
      throw new NotFoundHttpException("Invalid tamper plugin id: '$tamper'.");
    }

    $form['id'] = [
      '#type' => 'value',
      '#value' => $this->tamper->getPluginId(),
    ];

    /** @todo: Image effects has UUID - do we need that? */

    $form['data'] = [];
    $subform_state = SubformState::createForSubform($form['data'], $form, $form_state);

    /** @todo: Use configure name style as per image effects? */
    $form['data'] = $this->tamper->buildConfigurationForm($form['data'], $subform_state);
    $form['data']['#tree'] = TRUE;

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
    ];
//    $form['actions']['cancel'] = [
//      '#type' => 'link',
//      '#title' => $this->t('Cancel'),
//      '#url' => $this->feedsTamper->urlInfo('edit-form'),
//      '#attributes' => ['class' => ['button']],
//    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $subform_state = SubformState::createForSubform($form['data'], $form, $form_state);
    $this->tamper->validateConfigurationForm($form, $subform_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->cleanValues();

    $this->tamper->submitConfigurationForm($form['data'], SubformState::createForSubform($form['data'], $form, $form_state));

    if (!$this->tamper->getUuid()) {
      $this->feedsTamper->addTamper($this->source, $this->tamper);
    }
    $this->feedsTamper->save();

    drupal_set_message($this->t('The tamper plugin was successfully applied.'));
    $form_state->setRedirectUrl($this->feedsTamper->urlInfo('edit-form'));
  }

  /**
   * Converts an tamper ID into an object.
   *
   * @param string $tamper
   *   The tamper ID.
   *
   * @return \Drupal\tamper\TamperInterface
   *   The tamper object.
   */
  abstract protected function prepareTamper($tamper);

}
