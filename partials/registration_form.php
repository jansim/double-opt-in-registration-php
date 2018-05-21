<h1 class="title">Register</h1>

<form action="register.php" method="post">
  <? if ($email_error) { ?>
    <div class="error">You entered an invalid email address.</div>
  <? } ?>

  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" name="email" id="email" placeholder="email@example.com" value="<?= htmlentities($email); ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>