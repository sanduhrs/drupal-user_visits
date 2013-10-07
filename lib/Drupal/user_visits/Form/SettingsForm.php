<?php

/**
 * @file
 * Contains \Drupal\user_visits\Form\SettingsForm.
 */

namespace Drupal\user_visits\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\Context\ContextInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\ConfigFormBase;

class SettingsForm extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormID() {
    return 'user_visits.settings';
  }

  /**
   */
  public function buildForm(array $form, array &$form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->configFactory->get('user_visits.settings');

    $form['user_visits'] = array(
      '#type' => 'fieldset',
      '#title' => t('Display settings'),
      '#description' => t("Choose if you want the visitors to be displayed on the user's profile page or not. Alternatively you may use the provided !blocks to display a user's visitors.", array('!blocks' => l(t('blocks'), 'admin/structure/block'))),
      '#tree' => FALSE,
    );
    $form['user_visits']['user_visits_display'] = array(
      '#type' => 'radios',
      '#default_value' => $config->get('display'),
      '#options' => array(t("Don't display."), t('Display on user profile page')),
    );

    $entity_manager = \Drupal::entityManager();
    $role_storage = $entity_manager->getStorageController('user_role');

    $query = \Drupal::entityQuery('user_role');
    $query->condition('id', 'anonymous', '<>');
    $result = $query->execute();
    $roles = $role_storage->loadMultiple($result);

    $options = array();
    foreach ($roles as $id => $role) {
      /** @var \Drupal\user\RoleInterface[] $role */
      $options[$id] = $role->label();
    }

    $form['user_visits_role'] = array(
      '#type' => 'fieldset',
      '#title' => t('Role visibility'),
      '#description' => t("Choose roles and visits of selected roles will be not shown in user visit block."),
      '#tree' => FALSE,
    );
    $form['user_visits_role']['user_visits_hidden_roles'] = array(
      '#type' => 'select',
      '#title' => t('Hidden Roles'),
      '#description' => t('visits of selected roles will be not shown in user visit block.'),
      '#options' => $options,
      '#multiple' => TRUE,
      '#default_value' => $config->get('hidden_roles'),
    );

    return $form;
  }

  /**
   */
  public function validateForm(array &$form, array &$form_state) {
  }

  /**
   */
  public function submitForm(array &$form, array &$form_state) {
    $config = $this->configFactory->get('user_visits.settings');
    $config
      ->set('display', $form_state['values']['user_visits_display'])
      ->set('hidden_roles', $form_state['values']['user_visits_hidden_roles'])
      ->save();
  }

} 
