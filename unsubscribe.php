<?
// unsubscribe.php

require_once('vendor/autoload.php');

// let's define some outcomes for this page
define('STATUS_DEFAULT', 0);
define('STATUS_SUCCESS', 1);
define('STATUS_ERROR', 2);
$status = STATUS_DEFAULT;

$email = $_GET['email'];

$Registration = new Registration();
try {
	$Registration->fetchbyEmail($email);
	$Registration->unsubscribe();
	$status = STATUS_SUCCESS;
} catch(Exception $e) {
	$status = STATUS_ERROR;
}

switch($status) {
	case STATUS_SUCCESS:
		Renderer::page('unsubscribe_success', array(
			'email' => $Registration->email
		));
		break;
	
	case STATUS_ERROR:
		Renderer::page('unsubscribe_error', array());
		break;
	
	default:
		Renderer::error();
}

?>
