<?php

namespace Drupal\user_visits\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;

class Test extends ControllerBase {

  public function content() {
    $build = array();
    $build['#markup'] = $this->t('Test string');

    return $build;
  }

  public function content2($user) {
    print $user;
    return $user->id();
  }

}
