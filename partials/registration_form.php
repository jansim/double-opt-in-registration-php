<?php

// Check if the field is in the list of validation errors
// and echo the correct class name
function check($field) {
  global $validationErrors;
  if (in_array($field, $validationErrors)) {
    echo ' is-invalid';
  } else {
    // 'is-valid' creates a green border -> to do this validation should definetly be checked
    // therefore disabled for now
    // echo ' is-valid';
  }
}

?>

<h1 class="title">Register</h1>

<form action="register.php" method="post">
  <? if ($email_error) { ?>
    <div class="error">You entered an invalid email address.</div>
  <? } ?>

  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control<?check('email')?>" name="email" id="email" placeholder="email@example.com" value="<?= htmlentities($fields['email']); ?>">
    <div class="invalid-feedback">
      Please provide a proper email-address.
    </div>
  </div>

  <div class="form-group">
    <label for="name">Your name</label>
    <input type="name" class="form-control<?check('name')?>" name="name" id="name" placeholder="John Smith" value="<?= htmlentities($fields['name']); ?>">
    <div class="invalid-feedback">
      Please provide a proper name.
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>