<?php

class Registration {
  public $pdo;
  public $email;
  public $confirmationCode;
  public $confirmed;
  public $unsubscribed;

  public function __construct() {
    $this->pdo = Database::getPDO();
  }

  /**
   * Load data from the database into this object
   */
  private function loadFromDatabase($statement) {
    $object = $statement->fetch(PDO::FETCH_OBJ);

    // load the data from the database into this object
    $this->email = $object->email;
    $this->confirmationCode = $object->confirmationCode;
    $this->confirmed = $object->confirmed;
    $this->unsubscribed = $object->unsubscribed;
  }

  /**
   * Load the Registration by searching for the email address
   */
  public function fetchByEmail($email) {
    $statement = $this->pdo->prepare('select * from `Registration` where email = ?');
    $result = $statement->execute(array($email));
    $this->checkForError($result);
    $this->loadFromDatabase($statement);
  }

  /**
   * Load the Registration by searching for a matching confirmation code
   */
  public function fetchByConfirmationCode($confirmationCode) {
    $statement = $this->pdo->prepare('select * from `Registration` where confirmationCode = ?');
    $result = $statement->execute(array($confirmationCode));
    $this->checkForError($result);
    $this->loadFromDatabase($statement);
  }

  /**
   * create a new entry
   */
  public function initialize($fields) {
    $this->email = $fields['email'];

    $statement = $this->pdo->prepare('INSERT INTO `Registration` (' . implode(',', FIELDS) . ',`confirmationCode`,`confirmed`,`unsubscribed`) VALUES (?' . str_repeat(', ?', 2 + count(FIELDS)) . ')');

    // Concatenate both arrays
    $values = array_values(array_merge($fields, array($this->getConfirmationCode(), 0, 0)));
    $result = $statement->execute($values);

    $this->checkForError($result);
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
    $statement = $this->pdo->prepare('update `Registration` set `confirmed`="1" where email = ?');
    $result = $statement->execute(array($this->email));

    $this->checkForError($result);
  }

  /**
   * unsubscribe the user
   */
  public function unsubscribe() {
    $statement = $this->pdo->prepare('update `Registration` set `unsubscribed`="1" where email = ?');
    $result = $statement->execute(array($this->email));

    $this->checkForError($result);
  }

  private function checkForError($result) {
    if (!$result) {
      echo "Database error!";
      print_r($this->pdo->errorInfo());
      throw new Exception("Database Error:" . $this->pdo->errorInfo()[0]);
    }
  }
}

?>
