<?php

namespace RoyGoldman\DrupalEnvSettings;

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

}
