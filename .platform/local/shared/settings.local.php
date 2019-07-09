<?php
/**
 * 
@file * An example settings.local.php file for Drupal.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$config['advagg.settings']['enabled'] = FALSE;
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
// Add your database configuration here (and uncomment the block).
$databases['default']['default'] = array(
  'driver' => 'mysql',
  'host' => 'database',
  'username' => 'drupal8',
  'password' => 'drupal8',
  'database' => 'drupal8',
  'prefix' => '',
);
