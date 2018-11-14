<?php

namespace RoyGoldman\DrupalEnvSettings\ConfigGenerator;

use RoyGoldman\DrupalEnvSettings\Command\ConfigureCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * List of all commands provided by this package.
 */
class ApacheGenerator extends ConfigGeneratorBase {

  const LINE_TEMPLATE = 'SetEnv %s %s';

  const TEMPLATE_SOURCE = '<VirtualHost></VirtualHost>';

  /**
   * Creates an config generator instance.
   *
   * @param \RoyGoldman\DrupalEnvSettings\Command\ConfigureCommand $command
   *   Generate command.
   * @param string $name
   *   Generator name.
   */
  public function __construct(ConfigureCommand $command, $name) {
    parent::__construct($command, $name);
    $this
      ->addOption('template', InputOption::VALUE_OPTIONAL, 'Virtualhost template file.')
      ->addOption('out-file', InputOption::VALUE_OPTIONAL, 'Filename to write.', 'apache.conf')
      ->addOption('format', InputOption::VALUE_OPTIONAL, 'Format for output file.', 'vhost');
  }

  /**
   * {@inheritdoc}
   */
  public function generate(array $variables) {
    // Load config.
    $template = $this->getOption('template');
    $file = $this->getOption('out-file');
    $format = $this->getOption('format');

    // Construct configuration.
    $lines = [];
    foreach ($variables as $envvar => $value) {
      $lines[] = sprintf(static::LINE_TEMPLATE, $envvar, $value);
    }
    $template_snippet = implode("\n", $lines);

    // Load template, if provided.
    $source = '';
    if (!empty($template)) {
      $source = file_get_contents($template);
    }
    elseif ($format === 'vhost') {
      $source = static::TEMPLATE_SOURCE;
    }
    else {
      $source = '';
    }

    // For vhost configs, find the end of the vhost block.
    $source_after = '';
    if ($format === 'vhost') {
      if (($vhost_close = stripos($source, '</VirtualHost>')) !== FALSE) {
        $source_after = substr($source, $vhost_close);
        $source = substr($source, 0, $vhost_close);
      }
    }

    // Append generated source.
    $source .= "\n" . $template_snippet . "\n";

    // Reattach the end of the vhost file.
    if ($format === 'vhost') {
      $source .= $source_after;
    }

    // Write file.
    file_put_contents($file, $source);
  }

}
