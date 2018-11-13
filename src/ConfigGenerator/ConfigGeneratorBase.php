<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

/**
 * Providers a base for Config Generators.
 */
abstract class ConfigGeneratorBase implements ConfigGeneratorInterface {

  /**
   * Environmental variable values.
   *
   * @var array
   */
  protected $variables;

  /**
   * Generator config.
   *
   * @var array
   */
  protected $config;

  /**
   * Creates an config generator instance.
   *
   * @param array $config
   *   Generator config.
   * @param array $variables
   *   Environmental variable values.
   */
  public function __construct(array $config, array $variables) {
    $this->config = $config;
    $this->variables = $variables;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * {@inheritdoc}
   */
  public function getVariables() {
    return $this->variables;
  }

}
