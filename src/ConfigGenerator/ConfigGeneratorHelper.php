<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

use Composer\IO\IOInterface;

/**
 * List of all commands provided by this package.
 */
class ConfigGeneratorHelper {

  protected static $generators = [
    'apache' => 'RoyGoldman\DrupalEnvSettings\ConfigGenerator\ApacheGenerator',
  ];

  protected static function getGenerators() {
    return static::$generators;
  }

  protected static function getGenerator($name, $generator) {
    static::$generators[$name] = $generator;
  }

  protected static function generateMultiple($generator_config, $variables) {
    foreach ($generator_configs as $generator => $config) {
      static::generate($generator, $config, $variables);
    }
  }

  protected static function generate($generator, $config, $variables) {
    if (!isset(static::$generators[$generator])) {
      // @TODO Throw exceptions.
      return;
    }
    $generator_class = static::$generators[$generator
    $generator = new $generator_class($config, $variables);
    $generator->generate();
  }

}
