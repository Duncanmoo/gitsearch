<?php

namespace Drupal\gitsearch\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SearchForm.
 *
 * @package Drupal\gitsearch\Form
 */
class SearchForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gitsearch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $tempstore = \Drupal::service('user.private_tempstore')->get('gitsearch');
    $keywords = $tempstore->get('keywords');
    
    $form['keywords'] = [
      '#type' => 'search',
      '#title' => $this->t('Search phrase'),
      '#description' => $this->t('Search github repositories.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required' => TRUE,
      '#default_value' => $keywords,
    ];

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('Submit'),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (strlen($form_state->getValue('keywords')) <= 2) {
      $form_state->setErrorByName('keywords', t('Please enter a search phrase longer than 2 characters.'));
      // Clear the value in the user's session
      $tempstore = \Drupal::service('user.private_tempstore')->get('gitsearch');
      $tempstore->delete('keywords');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Store the value in the user's session
    $tempstore = \Drupal::service('user.private_tempstore')->get('gitsearch');
    $tempstore->set('keywords', $keywords);

  }

}
