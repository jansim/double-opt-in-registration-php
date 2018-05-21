<?
// confirm.php

require_once('vendor/autoload.php');

// let's define some outcomes for this page
define('STATUS_DEFAULT', 0);
define('STATUS_SUCCESS', 1);
define('STATUS_ERROR', 2);
$status = STATUS_DEFAULT;

$confirmationCode = $_GET['confirmationCode'];

$Registration = new Registration();
try {
	$Registration->fetchByConfirmationCode($confirmationCode);
	$Registration->confirm();
	
	$Email = new Email();
	$Email->subject = "Confirm Registration";
	$Email->recipient = $Registration->email;
	$Email->sender = 'noreply@example.com';
	$Email->message_html = file_get_contents('emails/confirm.htm');
	$Courier = new Courier();
	$Courier->send($Email);
	
	$status = STATUS_SUCCESS;
} catch(Exception $e) {
	$status = STATUS_ERROR;
}

switch($status) {
	case STATUS_SUCCESS:
		Renderer::page('confirmation_success', array(
			'email' => $Registration->email
		));
		break;
	
	case STATUS_ERROR:
		Renderer::page('confirmation_error', array());
		break;
	
	default:
		Renderer::error();
}

?>
