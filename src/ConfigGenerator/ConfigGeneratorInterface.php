<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

/**
 * Provides structure for config generators.
 */
interface ConfigGeneratorInterface {

  /**
   * Get generator config.
   *
   * @return array
   *   Array of generator config params.
   */
  public function getConfig();

  /**
   * Get configured environmental variables.
   *
   * @return array
   *   Array of environmental variables, where keys are variable names.
   */
  public function getVariables();

  /**
   * Generates the environmental configuration.
   */
  public function generate();

}
