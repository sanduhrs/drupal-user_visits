<?php

/**
 * @file
 * Contains \Drupal\user_visits\Plugin\Block\BlockBase.
 */

namespace Drupal\user_visits\Plugin\Block;

use Drupal\block\BlockBase as BaseBlockBase;
use \Drupal\Core\Plugin\ContainerFactoryPluginInterface;

abstract class BlockBase extends BaseBlockBase implements ContainerFactoryPluginInterface {


  public function __construct(array $configuration, $plugin_id, array $plugin_definition, EntityManager $entityManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityManager = $entityManager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity.manager'))
  }

  public function defaultConfiguration() {
    return array('items' => 5);
  }

  public function blockForm($form, &$form_state) {
    $form['items'] = array(
      '#type' => 'select',
      '#title' => t('Number of items'),
      '#default_value' => $this->configuration['items'],
      '#options' => drupal_map_assoc(
        array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25)
      ),
    );
    return $form;
  }

  public function blockSubmit($form, &$form_state) {
    $this->configuration['items'] = $form_state['values']['items'];
  }
  function user_visits_display_block_1($uid = NULL, $limit = 5) {
    $output = "";
    if (!$uid && (arg(0) == 'user' && is_numeric(arg(1)))) {
      $uid = arg(1);
    }

    $build = array();
    if ($uid) {
      $visitors = user_visits_latest($uid, $limit);
      if (is_array($visitors)) {
        foreach ($visitors as $visitor) {
          $account = $this->entityManager->getStorageController('user')->load($visitor->vuid);
          $output .= theme('user_visits', array('account' => $account, 'timestamp' => $visitor->visit));
        }
      }
      $build = array(
        '#theme' => 'user_visits_total',
        '#total' => user_visits_total($uid),
      );
      $output .= theme('user_visits_total', array('total' => user_visits_total($uid)));
    }
    return $output;
  }

}
