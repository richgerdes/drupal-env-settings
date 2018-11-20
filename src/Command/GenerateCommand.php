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
      ->setName('drupal:env-settings:generate')
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

    // Load Drupal package if avaiable and set webroot.
    $composer = $this->getComposer();
    $repositoryManager = $composer->getRepositoryManager();
    $localRepository = $repositoryManager->getLocalRepository();
    $drupalPackage = $localRepository->findPackages('drupal/core');
    if (empty($drupalPackage)) {
      // If package not found, try Drupal 7 varient.
      $drupalPackage = $localRepository->findPackages('drupal/drupal');
    }
    if (!empty($drupalPackage)) {
      // If package is found, find it's install location.
      $drupalPackage = reset($drupalPackage);
      $installationManager = $composer->getInstallationManager();
      $web_root = $installationManager->getInstallPath($drupalPackage);
      if ($drupalPackage->getName() === 'drupal/core') {
        // For drupal core (Drupal 8) remove the `core` from the path.
        $web_root = dirname($web_root);
      }
    }

    $output_file = $web_root . '/sites/' . $config['site'] . '/settings.php';
    $template_source = '';

    $local_default_template = dirname($web_root) . '/env-settings-template.php';
    $druapl_default_template = $web_root . '/sites/default/default.settings.php';

    // If tempalte file exists, load the source from the file.
    if (!empty($input->getOption('template'))) {
      $config['template'] = $input->getOption('template');
    }

    // If there is no template arguement attempt to load a default one.
    if (!isset($config['template']) || empty($config['template']) || (isset($config['template']) && $config['template'] === 'default')) {
      // If template file isn't specified, use the example one.
      if ((!isset($config['template']) || $config['template'] !== 'default') && file_exists($output_file)) {
        // If the output file exists, use that instead.
        $config['template'] = $output_file;
      }
      elseif (file_exists($local_default_template)) {
        // Attempt to load a template from the root of the project, if provided.
        $config['template'] = $local_default_template;
      }
      else {
        // Otherwise, load the drupal example file.
        $config['template'] = $druapl_default_template;
      }
    }

    // Load new source from template, if it exists.
    if ($config['template'] !== '_blank' && file_exists($config['template'])) {
      $template_source = file_get_contents($config['template']);
    }

    // Load env settings from composer.
    $env_settings = [];
    $root_package = $composer->getPackage();
    $extra = $root_package->getExtra();
    if (isset($extra['env-settings'])) {
      $env_settings = $extra['env-settings'];
    }

    // Generate the file.
    $generator = new SettingsGenerator();
    $generator->generate($output_file, $env_settings, $template_source);
  }

}
