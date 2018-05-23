<?php

class Database {
  private static $pdo;

  public static function getPDO()  {
    global $settings;

    if (!self::$pdo) {
      try {
        self::$pdo = new PDO('mysql:host='.$settings['mysql']['host'].';dbname='.$settings['mysql']['database'], $settings['mysql']['username'], $settings['mysql']['password']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        echo 'Error creating DB Connection:' . $e->getMessage();
      }
    }

    return self::$pdo;
  }

}
