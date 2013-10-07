<?php

/**
 * @file
 * Contains \Drupal\user_visits\Plugin\Block\LatestVisitors.
 */

namespace Drupal\user_visits\Plugin\Block;

use Drupal\block\BlockPluginInterface;

/**
 * @Block(
 *   id = "user_visits.my_latest_visitors",
 *   admin_label = @Translation("My latest visitors")
 * )
 */
class MyLatestVisitors extends BlockBase implements BlockPluginInterface {

  public function build() {
    $build = array();
    $build['#markup'] = $this->user_visits_display_block_1(NULL, 5);
    return $build;
  }
}
