<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

use RoyGoldman\DrupalEnvSettings\Command\ConfigureCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Providers a base for Config Generators.
 */
abstract class ConfigGeneratorBase implements ConfigGeneratorInterface {

  /**
   * Generator name for the command.
   *
   * @var string
   */
  protected $name;

  /**
   * Generator command.
   *
   * @var \RoyGoldman\DrupalEnvSettings\Command\ConfigureCommand
   */
  protected $command;

  /**
   * Generator command.
   *
   * @var \Symfony\Component\Console\Input\InputInterface
   */
  protected $input;

  /**
   * Creates an config generator instance.
   *
   * @param \RoyGoldman\DrupalEnvSettings\Command\ConfigureCommand $command
   *   Generate command.
   * @param string $name
   *   Generator name.
   */
  public function __construct(ConfigureCommand $command, $name) {
    $this->command = $command;
    $this->name = $name;
  }

  /**
   * {@inheritdoc}
   */
  public function addOption($option_name, $type = InputOption::VALUE_OPTIONAL, $description = '', $default = NULL) {
    $this->command->addOption($this->name . '--' . $option_name, NULL, $type, $description, $default);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOption($option_name) {
    return $this->getInput()->getOption($this->name . '--' . $option_name);
  }

  /**
   * {@inheritdoc}
   */
  public function setInput(InputInterface $input) {
    $this->input = $input;
  }

  /**
   * {@inheritdoc}
   */
  public function getInput() {
    if (empty($this->input)) {
      throw \Exception('You must initialize input with setInput() before calling getInput().');
    }
    return $this->input;
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
