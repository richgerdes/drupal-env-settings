<?php

namespace RoyGoldman\DrupalEnvSettings\SettingsGenerator;

/**
 * Defines structure for SettingsGenerator Helper.
 */
interface SettingsGeneratorInterface {

  /**
   * Generate settings file from variables.
   *
   * @param string $output_path
   *   File to write generated configuraiton.
   * @param array $env_settings
   *   Array of settings and their environmental variables.
   * @param string $template_source
   *   Template struture for the new settings file..
   */
  public function generate($output_path, array $env_settings, $template_source = '');

  /**
   * Insert code to dynamically load additional config from file.
   *
   * @param array &$code
   *   Generated code stmt list.
   */
  public function injectConfigLoader(array &$code);

  /**
   * Insert code to dynamically load additional config from file.
   *
   * @param array &$code
   *   Generated code stmt list.
   * @param array $env_settings
   *   Array of settings and their environmental variables.
   */
  public function generateVariableCode(array &$code, array $env_settings);

  /**
   * Generate code to load variable from environment by name.
   *
   * @param string $var_name
   *   Environmental variable name.
   */
  public function buildGetEnvNode($var_name);

  /**
   * Generate scaler from value.
   *
   * @param mixed $value
   *   Variable value.
   */
  public function getAsScaler($value);

  /**
   * Generate array items from value list.
   *
   * @param array $values
   *   Array values.
   */
  public function buildArrayItems(array $values);

}
