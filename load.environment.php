<?php

/**
 * This file is included very early. See autoload.files in composer.json and
 * https://getcomposer.org/doc/04-schema.md#files
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use DrupalFinder\DrupalFinder;

// Only attempt autoloading from .env if the library exists.
if (class_exists(Dotenv::class)) {
  // Locate .env file location.
  $dir = getcwd();
  $drupalFinder = new DrupalFinder();
  if ($drupalFinder->locateRoot($dir)) {
    // If Drupal is available, use a .env in the parent directory.
    $dir = realpath($drupalFinder->getDrupalRoot() . '/..');
  }
  // If we can't find Drupal, attempt to locate .env in the file tree.
  elseif (!file_exists($dir . '.env')) {
    // If its not in the cwd, step up one directory in search of .env.
    $dir = dirname($dir);
  }

  /**
   * Load any .env file. See /.env.example.
   */
  $dotenv = new Dotenv($dir);
  try {
    $dotenv->load();
  }
  catch (InvalidPathException $e) {
    // Do nothing. Production environments rarely use .env files.
  }
}
