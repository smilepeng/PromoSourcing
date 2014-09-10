<script type="text/javascript">

$(function(){

	
	$('ul.innernav a').click(function(event){
		event.preventDefault();
		$(this).parent().siblings('li').removeClass('selected'); 
		$(this).parent().addClass('selected');
	});
	
	$( "#popup_window" ).draggable( );
	$( "#search_window" ).draggable( );
	
	function showWindow(a) {
		formID=  $('input[name="noOfProducts"]').val();
		
		productTypeID	=  $('#productTypeID').val();
		requestID 		=  $('#requestAllID').val();
		//alert(formID+' + '+ productTypeID);
		if(productTypeID==0 )
			alert('Please Select one of the product type.');
		else
		{
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/requested_product_form_ajax'); ?>'+'/'+productTypeID +'/'+requestID  , {}, function () {
			$(this).removeClass('loading')
		});
		}
		
	};
	
	function hideWindow() {
		$('#popup_window').fadeOut(300, function () {
			$(this).html('');
			$(this).addClass('loading')
		});
	};
	
	function hideSearchWindow() {
		$('#search_window').fadeOut(300, function () {
			$(this).html('');
			$(this).addClass('loading')
		});
	};
	
	function showEditProduct(productID) {
		
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/edit_requested_product_form_ajax'); ?>'+'/'+productID  , {}, 
			function () {
				$(this).removeClass('loading')
			});
	};
	
	function showEditQuote(quoteID) {
		
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/edit_quote_form_ajax'); ?>'+'/'+quoteID  , {}, 
			function () {
				$(this).removeClass('loading')
			});
	};
	function showAddQuote(productID, templateID) {
			url = '<?php echo site_url('/admin/requests/add_quote_form_ajax'); ?>/'+productID;
			if( templateID )
			{
				url = url+'/'+templateID;
			}
			$('#popup_window').fadeIn(300).load(  url  , {}, 
			function () {
				$(this).removeClass('loading');
				$('a.submit_quotes').removeClass('hidden');
			});
	};
	
	function showSearchQuote(productID) {
			url = '<?php echo site_url('/admin/requests/search_quote_form_ajax'); ?>/'+productID;
			
			$('#search_window').fadeIn(300).load(  url  , {}, 
			function () {
				$(this).removeClass('loading')
			});
	};
	
	
	$(document).on('click', 'a.edit_requested_product', function () {
			
			showEditProduct(  $(this).attr('id'));
			return false
	});
	
	$(document).on('click', 'a.edit_quote', function () {
			//$('#quoteTempID').val();
			showEditQuote(  $(this).attr('id'));
			return false
	});
	
	$(document).on('click', 'a.add_quote', function () {
			
			showAddQuote(  $(this).attr('id'), $('#quoteTempID').val());
			return false
	});
	$(document).on('click', 'a.search_quote', function () {
			
			showSearchQuote(  $(this).attr('id') );
			return false
	});
	
		
	$('a.showWindow').bind('click', function () {
			showWindow(this);
			return false
		});
	$('a.addClient').bind('click', function () {
			addClient(this);
			return false
		});
	$(document).on('click', '#popup_window .window_close', function () {
			hideWindow();
			return false
		});
	
	$(document).on('click', '#search_window .window_close', function () {
			hideSearchWindow();
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
	$(document).on('click', 'a#save_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/save_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#requestedProduct').html( data );
			  });
			return false
		});
			
	$(document).on('click', 'a#save_quoted_product', function () {
			$.post( '<?php echo site_url('/admin/requests/save_quoted_product_ajax'); ?>', $( "#editQuoteForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#quotedProducts').html( data );
			  });
			return false
		});
				
	$(document).on('click', 'a#add_quoted_product', function () {
			$.post( '<?php echo site_url('/admin/requests/add_quoted_product_ajax'); ?>', $( "#addQuoteForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#quotedProducts').html( data );
			  });
			return false
		});
		
	$(document).on('click', 'a#search_quoted_product', function () {
			$('#found_products').html('searching....');
			$.post( '<?php echo site_url('/admin/requests/search_quoted_product_ajax'); ?>', $( "#searchQuoteForm" ).serialize() )
				.done(function( data ) {
				$('#found_products').html( data );
			  });
			return false
		});
	$(document).on('click', 'a.copy_quoted_product', function () {
			
			showAddQuote( $('#requestedProductID').val(), $(this).attr('id'));
			return false;
		});
	$(document).on('click', 'a.view_quoted_product', function () {
			
			$('#found_products').fadeIn(300).load(   '<?php echo site_url('/admin/requests/get_quoted_product_ajax'); ?>'+'/'+$(this).attr('id')  , {}, 
			function () {
				//$(this).removeClass('loading')
			});
		});
		
	$(document).on('click', '#drop a', function () {
			 $(this).parent().find('input').click();
		});	

	$(document).on('click', 'a#edit_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/save_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
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
	

	
});


</script>



<h1 class="headingleft">Requested Product </h1>

<div class="headingright">

</div>

<div class="clear"></div>

<?php if ($errors = validation_errors()): ?>
	<div class="error">
		<?php echo $errors; ?>
	</div>
<?php endif; ?>



<div id="details" class="tab">
	
	<input type="hidden" id="requestedProductID" name="requestedProductID" value="<?php echo   array_key_exists('productID', $requestedProduct )?$requestedProduct['productID']:'';?>">
	<h2 class="underline">Product Requirement</h2>
	<div id="requested_products">
	<div class="container_12">
			<div class="grid_3">
					<ul class="product_images" id="">
									<?php echo   array_key_exists('images', $requestedProduct )?$requestedProduct['images']:'';?>

					</ul>
					
				</div>
				<div class="grid_7">
				
					<p class="inline-label">
					<label for="productName" class="label">Product name:</label> <span class='fieldValue'> <?php echo array_key_exists('productName', $requestedProduct )?$requestedProduct['productName']:'' ; ?> </span>
				</p>
				<p class="inline-label">
				<label for="orderQuantities" class="label">Order Quantities:</label> <span  class='fieldValue'><?php echo  array_key_exists('orderQuantities', $requestedProduct )?$requestedProduct['orderQuantities']:'';?> 	</span>
				</p>
				<p class="inline-label">
				<label for="targetPriceAU" class="label">Target Price:</label> <span  class='fieldValue'> <?php echo array_key_exists('targetPriceAU', $requestedProduct )?$requestedProduct['targetPriceAU']:'' ; ?></span>
				</p>
				<p class="inline-label">
				<label for="requiredTime" class="label">Required Time:</label> <span  class='fieldValue'><?php echo  array_key_exists('requiredTime', $requestedProduct )?$requestedProduct['requiredTime']:'';?></span>
				</p>
				<?php echo $requestedProduct['featuresDetail']; ?>
				
				<p class="inline-label">
				<label for="description" class="label">Description:</label> <span  class='fieldValue'><?php echo   array_key_exists('description', $requestedProduct )?$requestedProduct['description']:'';?>	</span>
				</p>
				<p class="inline-label">		
				<label for="comment"  class="label">Email Comment:</label><span  class='fieldValue'> <?php echo   array_key_exists('comment', $requestedProduct )?$requestedProduct['comment']:'';?></span>
				
				</p>
				</div>
				<div class="grid_2">
					<span class="status-<?php echo strtolower($requestedProduct['status']);?>" ></span>
					<br/>
					<label for="accepter" class="label">Creater:</label><?php echo  $requestedProduct['repName']; ?>
						<br/>
						<label for="accepter" class="label">Created:</label><?php echo  $requestedProduct['dateCreated']; ?>
						<br/>
					<?php if ($requestedProduct['status'] !="O"  ): ?>
						<label for="accepter" class="label">Accepter:</label><?php echo  $requestedProduct['accepterName']; ?>
						<br/>
						<label for="accepter" class="label">Start:</label><?php echo  $requestedProduct['dateAccepted']; ?>
						<br/>
						<?php if ($requestedProduct['status'] =="Q"  ): ?>
						<label for="accepter" class="label">Quoted:</label><?php echo  $requestedProduct['dateQuoted']; ?>
						<br/>
						<?php endif; ?>
						
						<br/>
					<?php endif; ?>
					<?php if ( in_array('requests_delete', $this->permission->permissions) && $requestedProduct['status'] =="O"  ): ?>
					<a id="<?php echo  $requestedProduct['productID']; ?>" class="button icon-trash delete_requested_product">Delete</a>
					<br/>
				
					<?php endif; ?>
					<?php if ( in_array('requests_accept', $this->permission->permissions) && $requestedProduct['status'] =="O"  ): ?>
						<a href="<?php echo  site_url('/admin/requests/accept').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-trash delete_requested_product">Accept</a>
						<br/>
				
					<?php endif; ?>
					<?php if ( in_array('requests_release', $this->permission->permissions) && $requestedProduct['status'] =="A" && $requestedProduct['accepterID'] == $this->session->userdata('userID') ): ?>
						<a href="<?php echo  site_url('/admin/requests/release').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-outbox delete_requested_product">Release</a>
						<br/>
						<a id="<?php echo $requestedProduct['productID']; ?>" class="button icon-plus add_quote">Quote</a>
						<br/>
					<?php endif; ?>
					<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
						<a id="<?php echo  $requestedProduct['productID']; ?>" class="button icon-pencil edit_requested_product">Edit</a>
						<br/>
					<?php endif; ?>
					<?php if (in_array('requests', $this->permission->permissions)): ?>
						<a id="<?php echo  $requestedProduct['productID']; ?>" class="button icon-search search_quote">Search</a>
						<br/>
					<?php endif; ?>
				</div>
			</div>
			
	</div>
	
	<br class="clear" />
	
	<input type="hidden" name="quoteTempID" id="quoteTempID" >
	<h2 class="underline">Quotes 
	<?php if ( in_array('requests_accept', $this->permission->permissions) && $requestedProduct['status'] =="A" && $requestedProduct['accepterID'] == $this->session->userdata('userID') ): ?> 
	<a  class="button icon-plus add_quote"  id="<?php echo $requestedProduct['productID'];  ?>">Add Quote</a>
	<?php endif ?>
	</h2>
	<div id="quotedProducts" class="container_12">
	

<?php if ( !empty($quotes) ): ?>
		<?php $i=0; foreach($quotes as $quote):  $i ++;?>
					<div class="container_12">
			<div class="grid_3">
					<ul class="product_images" id="">

									<?php echo   array_key_exists('images', $requestedProduct )?$requestedProduct['images']:'';?>

					</ul>
				
				</div>
				<div class="grid_7">
				<p class="inline-label">
					<h2 >Quote <?php echo $i; ?></h2>
				</p>
				<p class="inline-label">
					<label for="productName" class="label">Product name:</label> <span class='fieldValue'> <?php echo array_key_exists('productName', $quote )?$quote['productName']:'' ; ?> </span>
				</p>
				<p class="inline-label"> <label class="label" for="orderQuantity">产品单价:</label> <span style=""> <table class="unit_prices"><thead><tr><td>Quantity</td><td>Unit Price</td></tr></thead><tbody><tr><td>111</td><td>$123</td></tr></tbody></table>  </span></p>
				<p class="inline-label"> <label class="label" for="unit_prices">Unit Price:</label> 
					<table class="unit_prices"><thead><tr><td>Quantity</td><td>Unit Price</td></tr></thead><tbody><tr><td>111</td><td>$123</td></tr></tbody></table>
				</p>
				<?php echo $quote['quotedPrices']; ?>
				
				<p class="inline-label">

				<label for="sampleCost" class="label">Sample Cost:</label> <span  class='fieldValue'> <?php echo array_key_exists('sampleCost', $quote )?$quote['sampleCost']:'n/a' ; ?></span>
				</p>
				<p class="inline-label">

				<label for="setupTime" class="label">Setup Time:</label> <span  class='fieldValue'><?php echo  array_key_exists('setupTime-Weeks', $quote )?$quote['setupTime-Weeks']:'n/a';?> Weeks</span>
				</p>
				<p class="inline-label">
				<label for="sampleTime" class="label">Sample Time:</label> <span  class='fieldValue'><?php echo  array_key_exists('sampleTime-Weeks', $quote )?$quote['sampleTime-Weeks']:'n/a';?> Weeks</span>
				</p>
				<p class="inline-label">
				<label for="deliveryTime" class="label">Delivery Time:</label> <span  class='fieldValue'><?php echo  array_key_exists('deliveryTime-Weeks', $quote )?$quote['deliveryTime-Weeks']:'n/a';?> Weeks</span>
				</p>
				<?php echo $quote['quotedFeaturesDetail']; ?>
				
	
				<p class="inline-label">
				<label for="factoryDetail" class="label">Factory Detail:</label> <span  class='fieldValue'><?php echo   array_key_exists('factoryDetail', $quote )?$quote['factoryDetail']:'n/a';?>	</span>
				</p>
				<p class="inline-label">		
				<label for="comment"  class="label">Comment:</label><span  class='fieldValue'> <?php echo   array_key_exists('comment', $quote )?$quote['comment']:'';?></span>
				
				</p>
				</div>
				<div class="grid_2">
				<a  class="button icon-copy edit_quote" id="<?php echo $quote['quotedProductID'];?>">Edit</a>
				<a  class="button icon-copy copy_quoted_product" id="<?php echo $quote['quotedProductID'];?>">Copy</a>
				
				</div>
			</div>
			<h2 class="underline">&nbsp;</h2>
			
	
		<?php endforeach; ?>
		
		<?php else: ?>

		<p >There is no quote yet.</p>

		<?php endif; ?>
		
		<br class="clear" />
	</div>
	<div class="headingright">
		<?php if ( in_array('requests_accept', $this->permission->permissions) && $requestedProduct['status'] =="A" && $requestedProduct['accepterID'] == $this->session->userdata('userID') ): ?> 
			<a  class="button save add_quote"  id="<?php echo $requestedProduct['productID'];  ?>">Add Quote</a>
			 
			<a class="button save submit_quotes <?php echo ( $noQuote  )? 'hidden':''; ?>" href="<?php echo site_url('/admin/requests/submit_quotes/'.$requestedProduct['productID']); ?>">Submit all Quotes</a>
			
		<?php endif ?>
			
		</div>	
	


</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	


<div id="popup_window" class="loading" style="display: none;">

</div>
<div id="search_window" class="loading" style="display: none;">

</div>
<div class="img_box" style="display: none;">
	<div class="img_placeholder">
		<img src="/PromoSourcing/assets/images/ps_logo.png" alt="Logo">
	</div>
</div>
