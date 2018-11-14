# Drupal Env Settings

This project provides a composer command to generate Drupal configuration and
settings using environmental variables.

## Usage

To include the commands in your project, simiply add this as a dependency to
your Composer project.

```
composer require roygoldman\drupal-env-settings
```

Once added to the project, add the desired configuration to your project's
`composer.json`. Configuration should be added to your project's `extras` block
as follows.

```
{
  ...
  "extra": {
    ...
    "env-settings": {
      "settings_name": "VALUE",
      "another_setting": {
        "sub_setting": "SUB_VALUE",
        "more_nested_settings": {
          "nested_setting": "NESTED_VALUE"
        }
      }
    }
    ...
  },
  ...
}
```

Settings configuraiton supports nested settings. Nested settings will be
converetd into a php array when they are added to the Drupal `settings.php`.

Once basic configuration is added, run the following commands to generate the
`settings.php` and config files respectfully.

```
composer drupal:env-settings:generate
```

```
composer drupal:env-settings:configure --generators={generator,list}
```

## Command Usage

The following documents the default command arguements for the two commands.

```
Usage:
  drupal:env-settings:generate [options]

Options:
  -s, --site[=SITE]              Drupal site name. [default: "default"]
  -t, --template[=TEMPLATE]      Settings file template.
```

```
Usage:
  drupal:env-settings:configure [options]

Options:
  -e, --from-env[=FROM-ENV]                  Take values from the current environemnt.
  -g, --generators=GENERATORS                Comma separated list of generators (ex. --generators=apache,dotenv). [default: ""]
      --apache--template[=APACHE--TEMPLATE]  Virtualhost template file.
      --apache--out-file[=APACHE--OUT-FILE]  Filename to write. [default: "apache.conf"]
      --apache--format[=APACHE--FORMAT]      Format for output file. [default: "vhost"]
      --dotenv--template[=DOTENV--TEMPLATE]  Output template file.
      --dotenv--out-file[=DOTENV--OUT-FILE]  Filename to write. [default: ".env"]
      --php--template[=PHP--TEMPLATE]        Output template file.
      --php--out-file[=PHP--OUT-FILE]        Filename to write. [default: "config.php"]
```

## Extending

If you find the need for additional generators, you can add them to them using
the following snippet.

```
<?php
use \RoyGoldman\DrupalEnvSettings\ConfigGenerator\ConfigGeneratorHelper;

ConfigGeneratorHelper::setGenerator($name, $class_name);
```

## Contributing

Please report any issues with this project and submit questions on to the GitHub
[issue queue](https://github.com/roygoldman/drupal-env-settings/issues/new). I
will do my best to follow up with any issues or questions as quickly. If you
develop a feature, or fix a bug, please open a pull request to add the changes
to the project.
