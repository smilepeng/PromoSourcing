<script type="text/javascript">

$(function(){
	$('div.category>span, div.category>input').hover(
		function() {
			if (!$(this).prev('input').attr('checked') && !$(this).attr('checked')){
				$(this).parent().addClass('hover');
			}
		},
		function() {
			if (!$(this).prev('input').attr('checked') && !$(this).attr('checked')){
				$(this).parent().removeClass('hover');
			}
		}
	);	
	$('div.category>span').click(function(){
		if ($(this).prev('input').attr('checked')){
			$(this).prev('input').attr('checked', false);
			$(this).parent().removeClass('hover');
		} else {
			$(this).prev('input').attr('checked', true);
			$(this).parent().addClass('hover');
		}
	});
	
	$('div.product_field>span, div.product_field>input').hover(
		function() {
			if (!$(this).prev('input').attr('checked') && !$(this).attr('checked')){
				$(this).parent().addClass('hover');
			}
		},
		function() {
			if (!$(this).prev('input').attr('checked') && !$(this).attr('checked')){
				$(this).parent().removeClass('hover');
			}
		}
	);	
	$('div.product_field>span').click(function(){
		if ($(this).prev('input').attr('checked')){
			$(this).prev('input').attr('checked', false);
			$(this).parent().removeClass('hover');
		} else {
			$(this).prev('input').attr('checked', true);
			$(this).parent().addClass('hover');
		}
	});
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

<h1 class="headingleft">Add Product Type<small>(<a href="<?php echo site_url('/admin/shop/product_types'); ?>">Back to Product Types</a>)</small></h1>

<div class="headingright">
	<input type="submit" value="Save Changes" class="button save" />
</div>

<div class="clear"></div>

<?php if ($errors = validation_errors()): ?>
	<div class="error">
		<?php echo $errors; ?>
	</div>
<?php endif; ?>

<ul class="innernav clear">
	<li class="selected"><a href="#details" class="showtab">Details</a></li>

</ul>

<br class="clear" />

<div id="details" class="tab">

	<h2 class="underline">Product Type Details</h2>
	
	<label for="productName">Product type name:</label>
	<?php echo @form_input('typeName',set_value('typeName', array_key_exists('typeName', $data )?$data['typeName']:''), 'id="typeName" class="input float-left"'); ?>
	<br class="clear" />
	<label for="category">Category: <small>[<a href="<?php echo site_url('/admin/shop/categories'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')">update</a>]</small></label>
	<div class="categories">
		<?php if ($categories): ?>
		<?php foreach($categories as $category): ?>
			<div class="category">
				<?php echo @form_checkbox('catsArray['.$category['catID'].']', $category['catName']); ?><span><?php echo ($category['parentID']) ? '<small>'.$category['parentName'].' &gt;</small> '.$category['catName'] : $category['catName']; ?></span>
			</div>
		<?php endforeach; ?>
		<?php else: ?>
			<div class="category">
				<strong>Warning:</strong> It is strongly recommended that you use categories or this may not appear properly. <a href="<?php echo site_url('/admin/blog/categories'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')"><strong>Please update your categories here</strong></a>.
			</div>
		<?php endif; ?>
	</div>
	<br class="clear" /><br />	
	<label for="category">Feature: <small>[<a href="<?php echo site_url('/admin/shop/add_product_field'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')">Add Feature</a>]</small></label>
	<div class="product_fields">
		<?php if ($product_fields): ?>
		<?php foreach($product_fields as $product_field): ?>
			<div class="product_field">
				<?php echo @form_checkbox('fieldsArray['.$product_field['fieldID'].']', $product_field['fieldName']); ?><span><?php echo $product_field['fieldName']; ?></span>
			</div>
		<?php endforeach; ?>
		<?php else: ?>
			<div class="product_field">
				<strong>Warning:</strong> It is strongly recommended that you use features or this may not appear properly. <a href="<?php echo site_url('/admin/blog/product_fields'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')"><strong>Please update your product features here</strong></a>.
			</div>
		<?php endif; ?>
	</div>
	<br class="clear" /><br />	
	<label for="description">Description:</label>
	<?php echo @form_textarea('description',set_value('description', array_key_exists('description', $data )?$data['description']:''), 'id="description" class="input float-left "'); ?>
	<br class="clear" />
	<span class="tip nolabel">The description briefly describes what kind of product is in this templates.</span>
	<br class="clear" /><br />
		
	<label for="tags">Tags: <br /></label>
	<?php echo @form_input('tags', set_value('tags', array_key_exists('tags', $data )?$data['tags']:''), 'id="tags" class="input float-left"'); ?>
	<span class="tip">Separate tags with a comma (e.g. &ldquo;places, hobbies, favourite work&rdquo;)</span>
	<br class="clear" />
	

	



</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>
