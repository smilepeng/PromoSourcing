<script type="text/javascript">
function hideAddress(){
	if (
		$('input#billingAddress1').val() == $('input#address1').val() &&
		$('input#billingAddress2').val() == $('input#address2').val() &&
		$('input#billingAddress3').val() == $('input#address3').val() &&
		$('input#billingCity').val() == $('input#city').val() &&
		$('select#billingState').val() == $('select#state').val() &&
		$('input#billingPostcode').val() == $('input#postcode').val() &&
		$('select#billingCountry').val() == $('select#country').val()									
	){
		$('div#billing').hide();
		$('input#sameAddress').attr('checked', true);
	}
}
$(function(){
	$('a.showtab').click(function(event){
		event.preventDefault();
		var div = $(this).attr('href'); 
		$('div.tab').hide();
		$(div).show();
	});
	$('ul.innernav a').click(function(event){
		event.preventDefault();
		$(this).parent().siblings('li').removeClass('selected'); 
		$(this).parent().addClass('selected');
	});
	$('div.tab:not(#tab1)').hide();	
	$('input#sameAddress').click(function(){
		$('div#billing').toggle(200);
		$('input#billingAddress1').val($('input#address1').val());
		$('input#billingAddress2').val($('input#address2').val());
		$('input#billingAddress3').val($('input#address3').val());
		$('input#billingCity').val($('input#city').val());
		$('select#billingState').val($('select#state').val());
		$('input#billingPostcode').val($('input#postcode').val());
		$('select#billingCountry').val($('select#country').val());
	});
	hideAddress();
});
</script>

<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" class="default">

	<h1 class="headingleft">Add User <small>(<a href="<?php echo site_url('/admin/users'); ?>">Back to Users</a>)</small></h1>

	<div class="headingright">
		<input type="submit" value="Save Changes" class="button" />
	</div>
	
	<div class="clear"></div>
	
	<?php if ($errors = validation_errors()): ?>
		<div class="error">
			<?php echo $errors; ?>
		</div>
	<?php endif; ?>
	<?php if (isset($message)): ?>
		<div class="message clear">
			<?php echo $message; ?>
		</div>
	<?php endif; ?>



<br class="clear" />

<div id="tab1" class="tab">

	
	
	<p class="inline-label">
	<label for="username" class="label">Username:</label>
	<?php echo @form_input('username', set_value('username', array_key_exists('username', $data )? $data['username']: '' ), 'id="username" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label class="label" for="password">Password:</label>
	<?php echo @form_password('password','', 'id="password" class="input float-left"'); ?>
	</p>


<?php if (@in_array('users_groups', $this->permission->permissions)): ?>
	<p class="inline-label">
	<label class="label" for="permissions">Group:</label>
	<?php 
		$values = array(
			0 => 'None'
		);

		if ($this->session->userdata('groupID') == '-1')
		{
			$values[-1] = 'Superuser';
		}
		
		$values[$this->site->config['groupID']] = 'Administrator';
		if ($groups)
		{
			foreach($groups as $group)
			{
				$values[$group['groupID']] = $group['groupName'];
			}
		}
		echo @form_dropdown('groupID',$values,set_value('groupIDs',  array_key_exists('groupID', $data )? $data['groupID'] :'' ), 'id="groupIDs" class="input float-left"'); 
	?>
	<span class="tip">To edit permissions click on `User Groups` in the Users tab.</span>
	</p>
	
<?php endif; ?>

	<p class="inline-label">
	<label class="label"  for="email">Email:</label>
	<?php echo @form_input('email',set_value('email',  array_key_exists('email', $data )? $data['email']:''), 'id="email" class="input float-left"'); ?>
	</p>
	<p class="inline-label">

	<label class="label"  for="firstName">First Name:</label>
	<?php echo @form_input('firstName',set_value('firstName',  array_key_exists('firstName', $data )? $data['firstName']: ''), 'id="firstName" class="input float-left"'); ?>
	</p>
	<p class="inline-label">

	<label class="label"  for="lastName">Last Name:</label>
	<?php echo @form_input('lastName',set_value('lastName',  array_key_exists('lastName', $data )? $data['lastName']: ''), 'id="lastName" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label  class="label" for="language" >Language</label>
	<?php 
		$values = array(
			'EN' => 'English',
			'CN' => 'Chinese'			
		);
		echo @form_dropdown('language',$values,set_value('language',  array_key_exists('language', $data )? $data['language']: ''), 'id="language" class="input float-left"'); 
	?>
	</p>
	<p class="inline-label">

	

	<label  class="label" for="active" >Active?</label>
	<?php 
		$values = array(
			1 => 'Yes',
			0 => 'No'			
		);
		echo @form_dropdown('active',$values,set_value('active',  array_key_exists('active', $data )? $data['active']: ''), 'id="active" class="input float-left"'); 
	?>
	</p>


<br />

</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>
