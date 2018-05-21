<?php
  // Configuration File

  // add new fields here to load them
  // (they still have to be added to the HTML form)
  const FIELDS = array(
    'email',
    'name'
  );

  // Settings
  $settings = array();
  // database connection
  $settings['mysql'] = array();
  $settings['mysql']['host'] = 'localhost';
  $settings['mysql']['username'] = 'mysqluser';
  $settings['mysql']['password'] = 'mysqlpassword';
  $settings['mysql']['database'] = 'optin_example';

?>