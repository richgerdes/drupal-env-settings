<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

/**
 * List of all commands provided by this package.
 */
class ConfigGeneratorHelper {

  /**
   * Defines list of generator plugins.
   *
   * @var string[]
   */
  protected static $generators = [
    'apache' => 'RoyGoldman\DrupalEnvSettings\ConfigGenerator\ApacheGenerator',
  ];

  /**
   * Get generators classes.
   *
   * @return string[]
   *   Generator class names.
   */
  public static function getGenerators() {
    return static::$generators;
  }

  /**
   * Get generators classes.
   *
   * @param string $name
   *   Generator short name.
   *
   * @return string[]
   *   Generator class name.
   */
  public static function getGenerator($name) {
    return (static::$generators[$name]) ?: NULL;
  }

  /**
   * Get generators classes.
   *
   * @param string $name
   *   Generator short name.
   * @param string $generator
   *   Generator class name.
   */
  public static function setGenerator($name, $generator) {
    static::$generators[$name] = $generator;
  }

  /**
   * Deconstruct nested configuration to get environmental variable names.
   *
   * @param array[] $variables
   *   Nested array of drupal settings and mapped to variable names.
   *
   * @return string[]
   *   Array where keys are variable names and values are drupal settings names.
   */
  public static function getVariableNames(array $variables) {
    $variable_values = [];
    foreach ($variables as $name => $value) {
      if (is_array($value)) {
        $sub_variables = static::getVariableNames($value);
        foreach ($sub_variables as $variable_name => $settings_name) {
          $variable_values[$variable_name] = $name . ':' . $settings_name;
        }
      }
      else {
        $variable_values[$value] = $name;
      }
    }
    return $variable_values;
  }

}
