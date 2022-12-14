<?php
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}
if (isset($_REQUEST['user_id'])){	
	$user_id=intval($_REQUEST['user_id']);
?>	
	<div class="form-group">
		<label for="fullname" class="col-sm-3 control-label">Nueva Contraseña</label>
		<div class="col-sm-6">
		  <input type="password" class="form-control" id="user_password_new" name="user_password_new" placeholder="******" pattern=".{6,}" required>
			<input value="<?php echo $user_id;?>" type="hidden" name="user_id" id="user_id">
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="fullname" class="col-sm-3 control-label">Repite contraseña</label>
		<div class="col-sm-6">
		  <input type="password" class="form-control" id="user_password_repeat" name="user_password_repeat" placeholder="******" pattern=".{6,}"  required>
		</div>
	  </div>
<?php } ?>	  