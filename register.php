<?
require_once('vendor/autoload.php');

use Respect\Validation\Validator as v;

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
		$email = $fields['email'];
		$Registration = new Registration();
		try {
			$Registration->initialize($email);
			$Email = new Email();
			$Email->subject = "Confirm Registration";
			$Email->recipient = $Registration->email;
			$Email->sender = 'noreply@example.com';
			$Email->message_html = file_get_contents('emails/register.htm');
			$Courier = new Courier();
			$Courier->send($Email);
			$registered = true;
		} catch (Exception $e) {
			$email_error = true;
		}
	}
} else if ($_GET) {
	// Get placeholder values from GET if they are set (default to null)
	extract_fields($_GET);
}

// Data to be passed to the the partials for rendering
$data = array(
	'fields' => $fields,

	'email_error' => $email_error,
	'validation_errors' => $validationErrors,
);

if ($registered) {
	// if the user has registered, let them know it worked
	Renderer::page('registration_success', $data);
} else {
	// otherwise display the form
	Renderer::page('registration_form', $data);
}
