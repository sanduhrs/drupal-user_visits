<?php
/* $Id$ */

/**
 * User visits 
 *
 * @author
 * Stefan Auditor >stefan.auditor@erdfisch.de>
 */

/**
 * Settings page
 */
function user_visits_settings() {
  $form['user_activity'] = array(
    '#type' => 'fieldset',
    '#title' => t('Display settings'),
    '#description' => t("Choose if you want the visitors to be displayed on the user's profile page or not. Alternatively you may use the provided !blocks to display a user's visitors.", array('!blocks' => l('blocks', 'admin/build/block'))),
  );
  $form['user_activity']['user_visits_display'] = array(
    '#type' => 'radios',
    '#default_value' => variable_get('user_visits_display', 0),
    '#options' => array(t("Don't display."), t('Display on user profile page')),
  );

  $roles = user_roles(TRUE);
  $form['user_activity_role'] = array(
    '#type' => 'fieldset',
    '#title' => t('Role visibility'),
    '#description' => t("Choose roles and visits of selected roles will be not shown in user visit block."),
  );
  $form['user_activity_role']['user_visits_hidden_roles'] = array(
      '#type' => 'select',
      '#title' => t('Hidden Roles'),
      '#description' => t('visits of selected roles will be not shown in user visit block.'),
      '#options' => $roles,
      '#multiple' => TRUE,
      '#default_value' => variable_get('user_visits_hidden_roles', array()),
  );
  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['advanced']['user_visits_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Custom record path'),
    '#description' => t('Set a custom path to record visits, e.g. <em>user/%</em>. This may be useful in case other modules override the default user profile.'),
    '#default_value' => variable_get('user_visits_path', ''),
  );
  return system_settings_form($form);
}