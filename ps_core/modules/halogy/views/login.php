<?php
	if(!$this->session->userdata('session_admin')) {
?>

	<script type="text/javascript">
	$(function(){
		$('#username').focus();
	});
	</script>


	<div id="container">

		
		
		
		<form action="" method="post" id="form-login">
			<ul class="inputs  large">
				<!-- The autocomplete="off" attributes is the only way to prevent webkit browsers from filling the inputs with yellow -->
				<li><span class="icon-user mid-margin-right"></span><input type="text" 		id="username" name="username" placeholder="Login" 	class="input-unstyled"   /></li>
				<li><span class="icon-lock mid-margin-right"></span><input type="password" 	id="password" name="password" placeholder="Password" class="input-unstyled"  /></li>
			</ul>
			
			<button type="submit" class="button glossy full-width huge">Login</button>
		</form>
		<?php if ($errors = validation_errors()): ?>
			
				<div class="big-message red-gradient">
					<span class="big-message-icon icon-warning with-text color"></span>
					<?php echo $errors; ?>
				</div>
		<?php endif; ?>
	</div>
	
	
	

<?php
	} else {
?>

	<h1>Logout</h1>

	<p><a href="/login/logout/">Click here to logout.</a></p>
	
<?php
	}
?>
