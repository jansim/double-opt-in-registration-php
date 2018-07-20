<?php

class Registration {
  public $pdo;
  public $email;
  public $confirmationCode;
  public $confirmed;
  public $unsubscribed;
  public $table;
  public $fields = array();

  public function __construct() {
    global $settings;
    $this->pdo = Database::getPDO();

    $this->table = $settings['mysql']['registration_table'];
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

    // populate fields array
    foreach (FIELDS as $name) {
      $this->fields[$name] = $object->$name;
    }
  }

  /**
   * Load the Registration by searching for the email address
   */
  public function fetchByEmail($email) {
    if (!$email) {
      throw new Exception("No email provided!");
    }

    $statement = $this->pdo->prepare('SELECT * FROM '.$this->table.' WHERE email = ?');
    $result = $statement->execute(array($email));
    $this->checkForError($result);
    $this->loadFromDatabase($statement);
  }

  /**
   * Load the Registration by searching for a matching confirmation code
   */
  public function fetchByConfirmationCode($confirmationCode) {
    if (!$confirmationCode) {
      throw new Exception("No ConfirmationCode provided!");
    }

    $statement = $this->pdo->prepare('SELECT * FROM '.$this->table.' WHERE confirmationCode = ?');
    $result = $statement->execute(array($confirmationCode));
    $this->checkForError($result);
    $this->loadFromDatabase($statement);
  }

  /**
   * create a new entry
   */
  public function initialize($fields) {
    $this->fields = $fields;
    $this->email = $fields['email'];

    // Remove any existing entries for this e-mail
    $this->pdo->prepare('DELETE FROM '.$this->table.' WHERE `email` = ?')->execute(array($this->email));


    // Prepare the statement
    $statement = $this->pdo->prepare('INSERT INTO '.$this->table.' ('.implode(',', FIELDS).',`confirmationCode`,`confirmed`,`unsubscribed`) VALUES (?'.str_repeat(', ?', 2 + count(FIELDS)).')');

    // Concatenate both arrays
    $values = array_values(array_merge($fields, array($this->getConfirmationCode(), 0, 0)));

    // Execute the statement
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
    $statement = $this->pdo->prepare('UPDATE '.$this->table.' SET `confirmed`="1" WHERE confirmationCode = ?');
    $result = $statement->execute(array($this->confirmationCode));

    $this->checkForError($result);
  }

  /**
   * unsubscribe the user
   */
  public function unsubscribe() {
    $statement = $this->pdo->prepare('UPDATE '.$this->table.' SET `unsubscribed`="1" WHERE email = ?');
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
