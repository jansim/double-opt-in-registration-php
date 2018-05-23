<?php
  // Configuration File

  // add new fields here to load them
  // (they still have to be added to the HTML form and the SQL Database!)
  const FIELDS = array(
    'email',
    'name'
  );

  // Settings
  global $settings;
  $settings = array();

  // From which mail should the e-mail notifications be sent?
  $settings['email']['sender'] = 'noreply@example.com';
  $settings['email']['registered_subject'] = 'Confirm Registration';
  $settings['email']['confirmed_subject'] = 'Successfully Registered';

  $settings['link_url_root'] = 'http://localhost/';

  // database connection
  $settings['mysql'] = array();
  $settings['mysql']['host'] = 'localhost';
  $settings['mysql']['username'] = 'root';
  $settings['mysql']['password'] = '';
  $settings['mysql']['database'] = 'optin';

?>