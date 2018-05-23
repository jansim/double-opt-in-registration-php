<?php

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Email class conceptualizes an email. 
 * It contains the information that the Courier
 * needs to send an email message to a recipient
 */
class Email {
  var $recipient,
    $name,
    $sender,
    $subject,
    $message_text,
    $message_html;

  public function __construct() {
    global $settings;
    $this->sender = $settings['email_sender']; // set default email-sender
  }

  // Send the e-mail
  // returns true or false depending on success
  public function send () {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        $mail->setFrom($this->sender);
        $mail->addReplyTo($this->sender);

        // Add a recipient
        $mail->addAddress($this->recipient, $this->name);

        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $this->subject;
        $mail->Body    = $this->message_html;
        $mail->AltBody = $this->message_text;

        $mail->send();

        return true; // success
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        var_dump($this);
        return false; // error
    }
  }
}

?>