<?php

namespace Drupal\custom_token\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\State;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CustomTokenForm.
 */
class CustomTokenForm extends FormBase {

  /**
   * The state store.
   *
   * @var State
   */
  protected $state;

  /**
   * @param StateInterface $state
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_token_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#weight' => '0',
      '#default_value' => (!empty($this->state->get('email'))) ? $this->state->get('email'): ""
    ];

    $form['email_text'] = [
      '#type' => 'text_format',
      '#title' => 'Email text',
      '#format' => 'basic_html',
      '#required' => TRUE,
      '#default_value' => (!empty($this->state->get('email_text'))) ? $this->state->get('email_text'): ""
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $email_text = $form_state->getValue('email_text');

    $this->state->set('email',$email);
    $this->state->set('email_text',$email_text['value']);
  }

}