<script type="text/javascript">

$(function(){

	
	$('input.save').click(function(){
		var requiredFields = 'input#feedbackID';
		var success = true;
		$(requiredFields).each(function(){
			if (!$(this).val().trim()) {
				$('div.panes').scrollTo(
					0, { duration: 400, axis: 'x' }
				);					
				$(this).addClass('error').prev('label').addClass('error');
				$(this).focus(function(){
					$(this).removeClass('error').prev('label').removeClass('error');
				});
				success = false;
			}
		});
		if (!success){
			$('div.tab').hide();
			$('div.tab:first').show();
		}
		return success;
	});	
});
</script>

<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" class="default">

<h1 class="headingleft">Process Feedback <small>(<a href="<?php echo site_url('/admin/feedback'); ?>">Back to Feedbacks</a>)</small></h1>

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
	<div class="message">
		<?php echo $message; ?>
	</div>
<?php endif; ?>
<?php echo @form_hidden('feedbackID', array_key_exists('feedbackID', $data)? $data['feedbackID']:'', 'id="feedbackID"');  ?>

<h2 class="underline">Feedback Message</h2>
<label for="subject">Subject:<?php echo array_key_exists('subject', $data)? $data['subject']:''; ?></label>

<br class="clear" />
<label for="name">Name:<?php echo array_key_exists('name', $data)? $data['name']:''; ?></label>

<br class="clear" />
<label for="email">Email:<?php echo array_key_exists('email', $data)? $data['email']:''; ?></label>

<br class="clear" />
<label for="message">Message:<?php echo array_key_exists('message', $data)? $data['message']:''; ?></label>
<br class="clear" />
<label for="datecreated">Post Date:<?php echo array_key_exists('dateCreated', $data)? $data['dateCreated']:''; ?></label>
<br class="clear" />

<h2 class="underline">Process Options</h2>

<label for="archived">Archived:</label>
<?php 
	$values = array(
		1 => 'Yes',
		0 => 'No ',
	);
	echo @form_dropdown('archived',$values,set_value('archived', $data['archived']), 'id="archived"'); 
?>
<br class="clear" />	
<label for="deleted">Deleted:</label>
<?php 
	$values = array(
		1 => 'Yes',
		0 => 'No ',
	);
	echo @form_dropdown('deleted',$values,set_value('deleted', $data['deleted']), 'id="deleted"'); 
?>
<br class="clear" />	
<label for="replied">Replied:</label>
<?php 
	$values = array(
		1 => 'Yes',
		0 => 'No ',
	);
	echo @form_dropdown('replied',$values,set_value('replied', $data['replied']), 'id="replied"'); 
?>
<br class="clear" />	
	
<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>
