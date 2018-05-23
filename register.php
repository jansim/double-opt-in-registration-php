<?
require_once('vendor/autoload.php');

use Respect\Validation\Validator as v;

$registered = false; // Did the user successfully register?
$error = false; // Was there an error along the way?
$fields = array(); // Array to hold the values of the fields
$validationErrors = array(); // Array to hold any errors during validation

// Extract fields from an array ($_POST or $_GET) if they are not found, they are set to null
function extract_fields($target_array) {
	global $fields;

	foreach (FIELDS as $name) {
		$fields[$name] = array_key_exists($name, $target_array) ? $target_array[$name] : null;
	}
}

if ($_POST) {
	// Extract fields from POST Array
	extract_fields($_POST);

	// ==== Validate ====
	// A list of errors during validation, the value should correspond to the fieldname
	if (!v::email()->validate($fields['email'])) {
		$validationErrors[] = 'email';
	}

	// Are all fields valid?
	$valid = count($validationErrors) == 0;

	// Register if all fields are valid
	if ($valid) {
		$Registration = new Registration();

		try {
			$Registration->initialize($fields);
			$Email = new Email();
			$Email->subject = "Confirm Registration";
			$Email->recipient = $Registration->email;

			$mailData = array(
				'link' => $settings['link_url_root'] . 'confirm.php?confirmationCode=' . urlencode($Registration->getConfirmationCode())
			);
			$Email->message_text = Renderer::renderMail('mail_confirmed_txt', $mailData);
			$Email->message_html = Renderer::renderMail('mail_confirmed', $mailData);

			$error = !$Email->send();
			$registered = true;
		} catch (Exception $e) {
			var_dump($e);
			$error = true;
		}
	}
} else {
	// Get placeholder values from GET if they are set (default to null)
	extract_fields($_GET);
}

// Data to be passed to the the partials for rendering
$data = array(
	'fields' => $fields,

	'error' => $error,
	'validation_errors' => $validationErrors,
);

if ($registered) {
	// if the user has registered, let them know it worked
	Renderer::page('registration_success', $data);
} else {
	// otherwise display the form
	Renderer::page('registration_form', $data);
}
