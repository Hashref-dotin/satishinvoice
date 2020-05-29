<?php
/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());

echo $GLOBALS['base_path']; 

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
menu_execute_active_handler();

