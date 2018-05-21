<?
// register.php

require_once('vendor/autoload.php');

if ($_POST) {
	$email = $_POST['email'];
	
	$EmailRegistration = new EmailRegistration();
	try {
		$EmailRegistration->initialize($email);
		$Email = new Email();
		$Email->subject = "Confirm Registration";
		$Email->recipient = $EmailRegistration->email;
		$Email->sender = 'noreply@example.com';
		$Email->message_html = file_get_contents('emails/register.htm');
		$Courier = new Courier();
		$Courier->send($Email);
		$registered = true;
	} catch (Exception $e) {
		$email_error = true;
	}
}

$data = array(
	'email_error' => $email_error,
	'email' => $email,
);

if ($registered) {
	// if the user has registered, let them know it worked
	Renderer::page('registration_success', $data);
} else {
	// otherwise display the form
	Renderer::page('registration_form', $data);
}
