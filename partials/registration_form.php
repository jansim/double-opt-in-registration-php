<form action="register.php" method="post">
<?
if ($email_error) {
?>
<div class="error">You entered an invalid email address.</div>
<?
}
?>
<input type="email" name="email" id="email" placeholder="email@example.com" value="<?= htmlentities($email); ?>" />
<input type="submit" value="Subscribe" />
</form>