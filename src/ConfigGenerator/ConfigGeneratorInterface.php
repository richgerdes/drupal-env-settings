<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Provides structure for config generators.
 */
interface ConfigGeneratorInterface {

  /**
   * Add an option for the generator option from the command.
   *
   * Options are mapped to command options by appending a prefix with the name
   * of the generator. For example, an `$option_name` of 'file' on the `apache`
   * generator will become `apache--file`.
   *
   * @param string $option_name
   *   Command option name.
   * @param int $type
   *   Option type. See `\Symfony\Component\Console\Input\InputOption`.
   * @param string $description
   *   Option description.
   * @param mixed $default
   *   Option default value.
   *
   * @return \RoyGoldman\DrupalEnvSettings\ConfigGenerator\ConfigGeneratorInterface
   *   Returns self.
   */
  public function addOption($option_name, $type = InputOption::VALUE_OPTIONAL, $description = '', $default = NULL);

  /**
   * Get the value of a generator option from the command input.
   *
   * See `RoyGoldman\DrupalEnvSettings\ConfigGenerator\ConfigGeneratorInterface::addOption()`
   *
   * @param string $option_name
   *   Command option name.
   *
   * @return mixed
   *   Option value.
   */
  public function getOption($option_name);

  /**
   * Set Symphony command input instance.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *   Symphony command input.
   */
  public function setInput(InputInterface $input);

  /**
   * Get Symphony command input instance.
   *
   * @return \Symfony\Component\Console\Input\InputInterface
   *   Symphony command input.
   */
  public function getInput();

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
   *
   * @param array $variables
   *   Environmental variable values.
   */
  public function generate(array $variables);

}
