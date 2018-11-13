<?php

namespace RoyGoldman\DrupalEnvSettings;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 * Composer plugin for handling drupal scaffold.
 */
class Plugin implements PluginInterface, Capable {

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return [
      'Composer\Plugin\Capability\CommandProvider' => 'RoyGoldman\DrupalEnvSettings\CommandProvider',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    // Do nothing.
  }

}
