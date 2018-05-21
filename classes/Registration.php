<?php
require_once('config.php');

class Registration {
  public $pdo;
  public $email;
  public $confirmationCode;
  public $confirmed;
  public $unsubscribed;

  public function __construct() {
    global $settings;
    $this->pdo = new PDO('mysql:host='.$settings['mysql']['host'].';dbname='.$settings['mysql']['database'], $settings['mysql']['username'], $settings['mysql']['password']);
  }

  /**
   * Load data from the database into this object
   */
  private function loadFromDatabase($result) {
    if (!$result) throw new Exception("MySQL Error: ".$this->pdo->errorInfo());

    $object = $result->fetch(PDO::FETCH_OBJ);

    // load the data from the database into this object
    $this->email = $object->email;
    $this->confirmationCode = $object->confirmationCode;
    $this->confirmed = $object->confirmed;
    $this->unsubscribed = $object->unsubscribed;

    // clean up
    $result->close();
  }

  /**
   * Load the Registration by searching for the email address
   */
  public function fetchByEmail($email) {
    $statement = $this->pdo->prepare('select * from `Registration` where email = ?');
    $result = $statement->execute(array($email));
    $this->loadFromDatabase($result);
  }

  /**
   * Load the Registration by searching for a matching confirmation code
   */
  public function fetchByConfirmationCode($confirmationCode) {
    $statement = $this->pdo->prepare('select * from `Registration` where confirmationCode = ?');
    $result = $statement->execute(array($confirmationCode));
    $this->loadFromDatabase($result);
  }

  /**
   * create a new entry
   */
  public function initialize($fields) {
    $statement = $this->pdo->prepare('insert into `Registration` (' . implode(',', FIELDS) . '`confirmationCode`,`confirmed`,`unsubscribed`) VALUES (?' . str_repeat(',?', 3) . ')');

    // Concatenate both arrays
    $values = array_merge($fields, array($this->getConfirmationCode(), 0, 0));
    $result = $statement->execute($values);

    if (!$result) throw new Exception("MySQL Error: ".$this->pdo->errorInfo());
  }

  /**
   * If no confirm code exists, generate a new one,
   * then return it.
   */
  public function getConfirmationCode() {
    if (!$this->confirmationCode) {
      $this->confirmationCode = md5(microtime());
    }
    return $this->confirmationCode;
  }

  /**
   * confirm the subscription
   */
  public function confirm() {
    $this->pdo->prepare('update `Registration` set `confirmed`="1" where email = ?');
    $result = $statement->execute(array($this->email));

    if (!$result) throw new Exception("MySQL Error: ".$this->pdo->errorInfo());
  }

  /**
   * unsubscribe the user
   */
  public function unsubscribe() {
    $this->pdo->prepare('update `Registration` set `unsubscribed`="1" where email = ?');
    $result = $statement->execute(array($this->email));

    if (!$result) throw new Exception("MySQL Error: ".$this->pdo->errorInfo());
  }
}

?>
