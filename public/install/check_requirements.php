<?php

declare(strict_types=1);
$requirements = [
      'php_version' => version_compare(phpversion(), '8.1', '>='),
      'composer'    => file_exists('composer.json'),
  ];

if ( ! $requirements['php_version']) {
    exit('PHP version 8.1 or higher is required.');
}

if ( ! $requirements['composer']) {
    exit('Composer is required. Please install composer and try again.');
}

header('Location: install.php?step=2');
exit;
