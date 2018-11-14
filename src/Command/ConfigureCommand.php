<?php

namespace RoyGoldman\DrupalEnvSettings\Command;

use Composer\Command\BaseCommand;
use RoyGoldman\DrupalEnvSettings\ConfigGenerator\ConfigGeneratorHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * The "drupal:scaffold" command class.
 *
 * Downloads scaffold files and generates the autoload.php file.
 */
class ConfigureCommand extends BaseCommand {

  protected $generators;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this
      ->setName('drupal:env-settings:configure')
      ->setDescription('Generate environment configuraiton files for settings.')
      ->addOption('from-env', 'e', InputOption::VALUE_OPTIONAL, 'Take values from the current environemnt.')
      ->addOption('generators', 'g', InputOption::VALUE_REQUIRED, 'Comma separated list of generators (ex. --generators=apache,dotenv).', '');

    // For each gnerator, create an instance and the load config from it.
    $generator_classes = ConfigGeneratorHelper::getGenerators();
    foreach ($generator_classes as $generator_name => $generator_class) {
      $this->generators[$generator_name] = new $generator_class($this, $generator_name);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $question_helper = $this->getHelper('question');

    // Load env settings from composer.
    $env_settings = [];
    $root_package = $this->getComposer()->getPackage();
    $extra = $root_package->getExtra();
    if (isset($extra['env-settings'])) {
      $env_settings = $extra['env-settings'];
    }

    // Load varaibles from helper.
    $variables = ConfigGeneratorHelper::getVariableNames($env_settings);
    $variable_values = [];

    // Load values for variables.
    foreach ($variables as $variable => $setting) {
      $value = NULL;
      // Get input value.
      if ($input->getOption('from-env') !== FALSE) {
        // If --from-env flag is set, pull value from the current environemnt.
        $value = getenv($variable);
      }
      else {
        // Prompt user to input value.
        $question = new Question('Please enter a value for ' . $variable . ' (' . $setting . ') [Leave empty to skip]: ');
        $value = $question_helper->ask($input, $output, $question);
      }
      // Ignore empty values.
      if (!empty($value)) {
        // Set value export.
        $variable_values[$variable] = $value;
      }
    }

    // Get generators from option.
    $generators = explode(',', $input->getOption('generators'));
    foreach ($generators as $generator_name) {
      if (!isset($this->generators[$generator_name])) {
        // If the generator instance for the name wasn't initilized, skip it.
        continue;
      }
      // Run the generator to export the configuration.
      $generator = $this->generators[$generator_name];
      $generator->setInput($input);
      $generator->generate($variable_values);
    }
  }

}
