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
	
	$( "#popup_window" ).draggable( );
	
	
	function showWindow(a) {
		formID=  $('input[name="noOfProducts"]').val();
		
		productTypeID=  $('#productTypeID').val();
		//alert(formID+' + '+ productTypeID);
		if(productTypeID==0 )
			alert('Please Select on of the product type.');
		else
		{
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/requested_product_form_ajax'); ?>'+'/'+productTypeID  , {}, function () {
			$(this).removeClass('loading')
		});
		}
		
	}
	
	function hideWindow() {
		$('#popup_window').fadeOut(300, function () {
			$(this).html('');
			$(this).addClass('loading')
		})
	}
	
	function showEditProduct(productID) {
		
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/edit_requested_product_form_ajax'); ?>'+'/'+productID  , {}, function () {
			$(this).removeClass('loading')
		});
		
		
	}
	
	$('a.showWindow').bind('click', function () {
			showWindow(this);
			return false
		});
	$(document).on('click', 'a.window_close', function () {
			hideWindow();
			return false
		});
	$(document).on('click', 'a#add_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/add_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#requested_products').html( data );
			  });
			return false
		});
		
	
	$(document).on('click', 'a#edit_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/edit_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#requested_products').html( data );
			  });
			return false
		});
	/*	
	//Add requested product
	$('.addproduct').click(function(event){
		event.preventDefault();
		formID=  $('input[name="noOfProducts"]').val();
		
		productTypeID=  $('#productTypeID').val();
		//alert(formID+' + '+ productTypeID);
		if(productTypeID==0 )
			alert('Please Select on of the product type.');
		else
		{
			$.get( '<?php echo site_url('/admin/requests/requested_product_form_ajax'); ?>'+'/'+formID+'/'+productTypeID, function( data ) {
			  $( "#requested_products" ).append( data );
			  $('input[name="noOfProducts"]').val( parseInt(formID)+1);
			});
		}

	});
	*/
	$('.addvar').click(function(event){
		event.preventDefault();
		$(this).parent().parent().siblings('div').toggle('400');
	});
	$('div#desc, div#variations').hide();

	$('input.save').click(function(){
		var requiredFields = 'input#productName, input#catalogueID';
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

<h1 class="headingleft">Add Request <small>(<a href="<?php echo site_url('/admin/requests/view_my_open'); ?>">Back to my requests</a>)</small></h1>

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

	<h2 class="underline">Request Details</h2>
	
	<label for="clientName">Client name:</label>
	<?php echo @form_input('clientName',set_value('clientName', array_key_exists('clientName', $data )?$data['clientName']:''), 'id="clientName" class="formelement"'); ?>
	<br class="clear" />
	<div id="requested_products" class="loader">
	

	
	</div>

	<?php echo @form_hidden('noOfProducts',set_value('noOfProducts', array_key_exists('noOfProducts', $data )?$data['noOfProducts']:1), 'id="noOfProducts"'); ?>
	<label for="productTypeID"><a href="#" class="addproduct showWindow"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_plus.gif" alt="Delete" class="padded"> Add Product  with Type:</a></label>
	<?php
		$options = array(
			'0' => 'Select One of Product types'
		);
		if ($productTypes):
			foreach ($productTypes as $productType):
				$options[$productType['productTypeID']] = $productType['typeName'];
			endforeach;
		endif;					
		echo @form_dropdown('productTypeID', $options, set_value('productTypeID',  array_key_exists('productTypeID', $data )?$data['productTypeID']:$options[0]), 'id="productTypeID" class="formelement"');
	?>	
	
	<br class="clear" />

	



</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	
</form>

<div id="popup_window" class="loading" style="display: none;">

</div>