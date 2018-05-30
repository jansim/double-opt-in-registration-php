<?php

class Database {
  const DATABASE_SETUP_FILE = 'supporting-files/database-setup.sql';
  private static $pdo;

  public static function getPDO()  {
    global $settings;

    if (!self::$pdo) {
      try {
        self::$pdo = new PDO('mysql:host='.$settings['mysql']['host'].';dbname='.$settings['mysql']['database'].';charset=UTF8', $settings['mysql']['username'], $settings['mysql']['password']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        echo 'Error creating DB Connection:' . $e->getMessage();
      }
    }

    return self::$pdo;
  }

  public static function setup() {
    global $settings;

    $pdo = self::getPDO();
    $sql = file_get_contents(self::DATABASE_SETUP_FILE);
    $sql = str_replace('?', $settings['mysql']['registration_table'], $sql); // replace question mark with table name
    $success = $pdo->exec($sql);
    if ($success === false) {
      echo 'Error setting up Database.';
      print_r($pdo->errorInfo(), true);
    }
  }

}
