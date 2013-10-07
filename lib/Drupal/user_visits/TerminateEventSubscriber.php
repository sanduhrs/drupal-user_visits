<?php

/**
 * @file
 * Contains \Drupal\user_visits\TerminateEventSubscriber.
 */

namespace Drupal\user_visits;

use Drupal\Core\Session\AccountInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TerminateEventSubscriber implements EventSubscriberInterface {

  /**
   * @var ConfigFactory
   */
  private $configFactory;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  public function __construct(AccountInterface $user, ConfigFactory $configFactory) {
    $this->user = $user;
    $this->configFactory = $configFactory;
  }

  protected function user_visits_is_hidden() {
    $intersect = array_intersect_key($this->user->getRoles(), $this->configFactory->get('user_visits.settings')->get('hidden_roles'));
    return count($intersect) ? TRUE : FALSE;
  }

  public function terminate(PostResponseEvent $event) {
    $request = $event->getRequest();
    $referer = $request->server->get('HTTP_REFERER');

    // Don't count if user no has access to profile.
    if (!$this->user->hasPermission('access user profiles')) {
      return;
    }

    // Don't count anonymous-clicks.
    if ($this->user->isAnonymous()) {
      return;
    }

    // Don't count if role is hidden.
    if ($this->user_visits_is_hidden($this->user)) {
      return;
    }

    // Try not to count clicks from the user's other profile pages.
    if (strpos($referer, arg(0) . '/' . arg(1))) {
      return;
    }
    // Record visits on user profile pages.
    elseif ($request->attributes->get(RouteObjectInterface::ROUTE_NAME) == 'user.view') {
      // Don't count self-clicks.
      $visited_user = $request->attributes->get('user');
      if ($this->user->id() != $visited_user->id()) {
        // Count view.
        user_visits_count($this->user->id(), $visited_user->id());
      }
    }
  }

  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * array('eventName' => 'methodName')
   *  * array('eventName' => array('methodName', $priority))
   *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
   *
   * @return array The event names to listen to
   *
   * @api
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::TERMINATE] = array('terminate');

    return $events;
  }


} 
