<?php

/**
 * @file
 * Contains \Drupal\user_visits\Converter.
 */

namespace Drupal\user_visits;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class Converter implements ParamConverterInterface {

  /**
   * Allows to convert variables to their corresponding objects.
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return mixed|null
   *   The converted parameter value.
   */
  public function convert($value, $definition, $name, array $defaults, Request $request) {
    return $value + 1;
  }

  /**
   * Determines if the converter applies to a specific route and variable.
   *
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param \Symfony\Component\Routing\Route $route
   *   The route to consider attaching to.
   *
   * @return bool
   *   TRUE if the converter applies to the passed route and parameter, FALSE
   *   otherwise.
   */
  public function applies($definition, $name, Route $route) {
    return $route->getPath() == '/admin/config/people/user_visits/{user}/test';
  }

} 
