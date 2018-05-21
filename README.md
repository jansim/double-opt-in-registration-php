Double Opt-In Email Registration With PHP
=============================

This is a tempalte to create a simple double-opt-in in PHP. Multiple fields as well as proper validation for this fields can be easily added.

## Configuration
This project uses [composer](https://getcomposer.org/) and to initiliaze it properly you have to run `composer install` once.

Set up your settings file to reflect your MySQL server settings in config.php.
```
    $settings['mysql']['server'] = 'localhost';
    $settings['mysql']['username'] = 'mysqluser';
    $settings['mysql']['password'] = 'mysqlpassword';
    $settings['mysql']['schema'] = 'optin_example';
```

`$settings['link_url_root']` should correnspond to the root url of these double-opt-in pages, it is used to generate the links in the e-mails.

Then You should be good to go.

## Attribution
This project is based upon [backupbrain/double-opt-in-registration-php](https://github.com/backupbrain/double-opt-in-registration-php).
