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
	$('textarea#body').focus(function(){
		$('.previewbutton').show();
	});
	$('textarea#body').blur(function(){
		preview(this);
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
	<li><a href="#desc" class="showtab">Description</a></li>
	<li><a href="#variations" class="showtab">Options &amp; Variations</a></li>	
</ul>

<br class="clear" />

<div id="details" class="tab">

	<h2 class="underline">Product Type Details</h2>
	
	<label for="productName">Product type name:</label>
	<?php echo @form_input('typeName',set_value('typeName', array_key_exists('typeName', $data )?$data['typeName']:''), 'id="typeName" class="formelement"'); ?>
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
	<?php echo @form_textarea('description',set_value('description', array_key_exists('description', $data )?$data['description']:''), 'id="description" class="formelement short"'); ?>
	<br class="clear" />
	<span class="tip nolabel">The description briefly describes what kind of product is in this templates.</span>
	<br class="clear" /><br />
		
	<label for="tags">Tags: <br /></label>
	<?php echo @form_input('tags', set_value('tags', array_key_exists('tags', $data )?$data['tags']:''), 'id="tags" class="formelement"'); ?>
	<span class="tip">Separate tags with a comma (e.g. &ldquo;places, hobbies, favourite work&rdquo;)</span>
	<br class="clear" />
	

	



</div>

<div id="desc" class="tab">	

	<h2 class="underline">Product Type Features</h2>
	<label for="feature">Feature: <small>[<a href="<?php echo site_url('/admin/shop/product_fields'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')">update</a>]</small></label>
	<div class="product_fields">
		<?php if ($product_fields): ?>
		<?php foreach($product_fields as $product_field): ?>
			<div class="product_field">
				<?php echo @form_checkbox('featuressArray['.$product_field['catID'].']', $product_field['catName']); ?><span><?php echo ($product_field['parentID']) ? '<small>'.$product_field['parentName'].' &gt;</small> '.$product_field['catName'] : $product_field['catName']; ?></span>
			</div>
		<?php endforeach; ?>
		<?php else: ?>
			<div class="product_field">
				<strong>Warning:</strong> It is strongly recommended that you use product features or this may not appear properly. <a href="<?php echo site_url('/admin/shop/product_fields'); ?>" onclick="return confirm('You will lose any unsaved changes.\n\nContinue anyway?')"><strong>Please update your product features here</strong></a>.
			</div>
		<?php endif; ?>
	</div>
	<br class="clear" /><br />	

</div>

<div id="variations" class="tab">	
	
	<h2 class="underline">Options</h2>
	
	<label for="freePostage">Free Shipping?</label>
	<?php 
		$values = array(
			0 => 'No',
			1 => 'Yes',
		);
		echo @form_dropdown('freePostage',$values,set_value('freePostage', array_key_exists('freePostage', $data )?$data['freePostage']:''), 'id="freePostage"'); 
	?>
	<br class="clear" />

	<label for="files">File:</label>
	<?php
		$options = '';
		$options[0] = 'This product is not a file';			
		if ($files):
			foreach ($files as $file):
				$ext = @explode('.', $file['filename']);
				$options[$file['fileID']] = $file['fileRef'].' ('.strtoupper($ext[1]).')';
			endforeach;
		endif;					
		echo @form_dropdown('fileID',$options,set_value('fileID', array_key_exists('fileID', $data )?$data['fileID']:''),'id="files" class="formelement"');
	?>
	<span class="tip">You can make this product a downloadable file (e.g. a premium MP3 or document).</span>
	<br class="clear" />

	<label for="bands">Shipping Band:</label>
	<?php
		$options = '';
		$options[0] = 'No product is not restricted';			
		if ($bands):
			foreach ($bands as $band):
				$options[$band['bandID']] = $band['bandName'];
			endforeach;
		endif;					
		echo @form_dropdown('bandID', $options, set_value('bandID', array_key_exists('bandID', $data )?$data['bandID']:''),'id="bands" class="formelement"');
	?>
	<span class="tip">You can restrict this product to a shipping band if necessary.</span>
	<br class="clear" /><br />
	
	<h2 class="underline">Variations</h2>

	<div id="variation1">
		<div class="addvars">
			<p><a href="#" class="addvar"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_plus.gif" alt="Delete" class="padded" /> Add <?php echo $this->site->config['shopVariation1']; ?> Variations</a></p>
			<br class="clear" />				
		</div>
		<div class="showvars" style="display: none;">

			<?php foreach (range(1,5) as $x): $i = $x-1; ?>
				
			<label for="variation1-<?php echo $x; ?>"><?php echo $this->site->config['shopVariation1']; ?> <?php echo $x; ?>:</label>
			<?php if ( $variation1 && array_key_exists($i, $variation1)): echo @form_input('variation1-'.$x,set_value('variation1-'.$x, $variation1[$i]['variation']), 'id="variation1-'.$x.'" class="formelement"'); ?><span class="price"><strong><?php echo currency_symbol(); ?></strong></span><?php echo @form_input('variation1_price-'.$x, (is_numeric($variation1[$i]['price']) )?number_format(set_value('variation1_price-'.$x, $variation1[$i]['price']),2):'', 'class="formelement small"'); 
		
				else:
				echo @form_input('variation1-'.$x,set_value('variation1-'.$x, ''), 'id="variation1-'.$x.'" class="formelement"'); ?>
				<span class="price"><strong><?php echo currency_symbol(); ?>
				</strong></span>
				<?php echo @form_input('variation1_price-'.$x,'', 'class="formelement small"');
				endif;
			?>
			<br class="clear" />		

			<?php endforeach; ?>		
										
		</div>
	</div>


	<div id="variation2">
		<div class="addvars">
			<p><a href="#" class="addvar"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_plus.gif" alt="Delete" class="padded" /> Add <?php echo $this->site->config['shopVariation2']; ?> Variations</a></p>
			<br class="clear" />				
		</div>
		<div class="showvars" style="display: none;">
			
			<?php foreach (range(1,5) as $x): $i = $x-1; ?>
				
			<label for="variation2-<?php echo $x; ?>"><?php echo $this->site->config['shopVariation2']; ?> <?php echo $x; ?>:</label>
			<?php if ( $variation2 && array_key_exists($i, $variation2)): echo @form_input('variation2-'.$x,set_value('variation2-'.$x, $variation2[$i]['variation']), 'id="variation2-'.$x.'" class="formelement"'); ?><span class="price"><strong><?php echo currency_symbol(); ?></strong></span><?php echo @form_input('variation2_price-'.$x, (is_numeric($variation2[$i]['price']) )?number_format(set_value('variation2_price-'.$x, $variation2[$i]['price']),2):'', 'class="formelement small"'); 
		
				else:
				echo @form_input('variation2-'.$x,set_value('variation2-'.$x, ''), 'id="variation2-'.$x.'" class="formelement"'); ?>
				<span class="price"><strong><?php echo currency_symbol(); ?>
				</strong></span>
				<?php echo @form_input('variation2_price-'.$x,'', 'class="formelement small"');
				endif;
			?>
			<br class="clear" />		

			<?php endforeach; ?>
										
		</div>
	</div>

	<div id="variation3">
		<div class="addvars">
			<p><a href="#" class="addvar"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_plus.gif" alt="Delete" class="padded" /> Add <?php echo $this->site->config['shopVariation3']; ?> Variations</a></p>
			<br class="clear" />				
		</div>
		<div class="showvars" style="display: none;">
			
			<?php foreach (range(1,5) as $x): $i = $x-1; ?>
				
			<label for="variation3-<?php echo $x; ?>"><?php echo $this->site->config['shopVariation3']; ?> <?php echo $x; ?>:</label>
			<?php if ($variation3 &&  array_key_exists($i, $variation3)): echo @form_input('variation3-'.$x,set_value('variation3-'.$x, $variation3[$i]['variation']), 'id="variation3-'.$x.'" class="formelement"'); ?><span class="price"><strong><?php echo currency_symbol(); ?></strong></span><?php echo @form_input('variation3_price-'.$x, (is_numeric($variation3[$i]['price']) )?number_format(set_value('variation3_price-'.$x, $variation3[$i]['price']),2):'', 'class="formelement small"'); 
		
				else:
				echo @form_input('variation3-'.$x,set_value('variation3-'.$x, ''), 'id="variation3-'.$x.'" class="formelement"'); ?>
				<span class="price"><strong><?php echo currency_symbol(); ?>
				</strong></span>
				<?php echo @form_input('variation3_price-'.$x,'', 'class="formelement small"');
				endif;
			?>
			<br class="clear" />		

			<?php endforeach; ?>
										
		</div>
	</div>

</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>
