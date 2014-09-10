<script type="text/javascript">

$(function(){
	
	$('a.showtab').click(function(event){
		event.preventDefault();
		var div = $(this).attr('href'); 
		$('div#details, div#desc, div#variations').hide();
		$(div).show();
	});
	$('ul.innernav a').click(function(event){
		event.preventDefault();
		$(this).parent().siblings('li').removeClass('selected'); 
		$(this).parent().addClass('selected');
	});
	$('.addvar').click(function(event){
		event.preventDefault();
		$(this).parent().parent().siblings('div').toggle('400');
	});
	$('div#desc, div#variations').hide();

	$('input.save').click(function(){
		var requiredFields = 'input#typeName, input#catalogueID';
		var success = true;
		$(requiredFields).each(function(){
			if (!$(this).val()) {
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

<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data" class="default">

<h1 class="headingleft">View/Edit Product feature<small>(<a href="<?php echo site_url('/admin/shop/product_fields'); ?>">Back to Product Features</a>)</small></h1>

<div class="headingright">
	<input type="submit" value="Save Changes" class="button save" />
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
<ul class="innernav clear">
	<li class="selected"><a href="#details" class="showtab">Details</a></li>
	
	
</ul>

<br class="clear" />

<div id="details" class="tab">

	<h2 class="underline">Product Feature Details</h2>
	<span class="tip">You can not modify product feature name and type.</span>
	<label for="productName">Product feature name:</label>
	<?php echo @form_input('fieldName',set_value('fieldName', array_key_exists('fieldName', $data )?$data['fieldName']:''), 'id="fieldName" class="input float-left" readonly="true"'); ?>
	<br class="clear" />
	<label for="productName">中文名称:</label>
	<?php echo @form_input('fieldNameCN',set_value('fieldNameCN', array_key_exists('fieldNameCN', $data )?$data['fieldNameCN']:''), 'id="fieldNameCN" class="input float-left"'); ?>
	<br class="clear" />
	<label for="productName">Type:</label>
	<?php
		
		echo @form_input('fieldType',set_value('fieldType', array_key_exists('fieldType', $data )?$data['fieldType']:''), 'id="fieldType" class="input float-left" readonly="true"'); 
		
	?>	
	
	<br class="clear" />
	<label for="valueSet">Value Set:</label>
	<?php echo @form_textarea('valueSet',set_value('valueSet', array_key_exists('valueSet', $data )?$data['valueSet']:''), 'id="valueSet" class="input float-left"  '); ?>
	<span class="tip">Use ',' to separate each values.(e.g. plastic, metal, wood) It will be used as drop down values in Combo Box.</span>
	<br class="clear" />
	<label for="sampleValue">Sample Value:</label>
	<?php echo @form_input('sampleValue',set_value('sampleValue', array_key_exists('sampleValue', $data )?$data['sampleValue']:''), 'id="sampleValue" class="input float-left"  '); ?>
	<br class="clear" />
	<label for="defaultValue">Default Value:</label>
	<?php echo @form_input('defaultValue',set_value('defaultValue', array_key_exists('defaultValue', $data )?$data['defaultValue']:''), 'id="defaultValue" class="input float-left" '); ?>
	<span class="tip">Use value from one of value set.</span>
	<br class="clear" />
	
</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>
