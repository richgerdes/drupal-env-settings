<?php

namespace RoyGoldman\DrupalEnvSettings\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use RoyGoldman\DrupalEnvSettings\SettingsGenerator\SettingsGenerator;

/**
 * The "drupal:env" command class.
 *
 * Downloads scaffold files and generates the autoload.php file.
 */
class GenerateCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this
      ->setName('drupal:env')
      ->setDescription('Generage Drupal Settings file using environmental variable configuriaton.')

      ->addOption('site', 's', InputOption::VALUE_OPTIONAL, 'Drupal site name.', 'default')
      ->addOption('template', 't', InputOption::VALUE_OPTIONAL, 'Settings file template.', NULL);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $config = [];
    $config['site'] = $input->getOption('site');

    $web_root = getcwd();

    $composer = $this->getComposer();

    $repositoryManager = $composer->getRepositoryManager();
    $localRepository = $repositoryManager->getLocalRepository();
    $drupalPackage = $localRepository->findPackages('drupal/core');
    if (!empty($drupalPackage)) {
      $drupalPackage = $localRepository->findPackages('drupal/drupal');
    }

    if (!empty($drupalPackage)) {
      $drupalPackage = reset($drupalPackage);
      $installationManager = $composer->getInstallationManager();
      $web_root = $installationManager->getInstallPath($drupalPackage);
    }

    if (!empty($input->getOption('template'))) {
      $config['template'] = $input->getOption('template');
    }
    else {
      // If template file isn't specified, use the example one.
      $config['template'] = $web_root . '/sites/default/example.settings.php';
    }

    $output_file = $web_root . '/sites/' . $config['site'] . '/settings.php';

    $template_source = '';
    if (file_exists($output_file)) {
      $template_source = file_get_contents($output_file);
    }
    else {
      $template_source = file_get_contents($config['template']);
      $this->mkdirRecursive(dirname($output_file));
    }

    $composer = $this->getComposer();
    $root_package = $composer->getPackage();
    $extra = $root_package->getExtra();

    $env_settings = [];
    if (isset($extra['env-settings'])) {
      $env_settings = $extra['env-settings'];
    }

    $generator = new SettingsGenerator();
    $generator->generate($output_file, $env_settings, $template_source);
  }

  /**
   * {@inheritdoc}
   */
  protected function mkdirRecursive($output_file) {
    $path = '';
    foreach (explode('/', $output_file) as $dir) {
      $path .= $dir;
      mkdir($path, '0664');
      $path .= '/';
    }
  }

}
