<?php

/**
 * @file
 * Contains \Drupal\user_visits\Plugin\Block\LatestVisitors.
 */

namespace Drupal\user_visits\Plugin\Block;

/**
 * @Block(
 *   id = "user_visits.latest_visitors",
 *   admin_label = @Translation("Latest visitors")
 * )
 */
class LatestVisitors extends BlockBase {

  public function build() {
    $build = array();
    $build['#markup'] = $this->user_visits_display_block_1(\Drupal::currentUser()->id(), 5);
    return $build;
  }

}
