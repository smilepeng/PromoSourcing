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
	
	function addClient(){
		clientName = $('#clientName').val() ;
		if( clientName.length > 0){
			$('a.addClient').addClass('loading');
			console.log($( "#clientDetail" ).serialize());
			$.post( '<?php echo site_url('/admin/requests/add_client_ajax'); ?>', $( "#clientDetail" ).serialize() ).done(function( data ) {
					$('a.addClient').fadeOut();
					$('#step2').fadeIn();
					
					//console.log(data);
					info= jQuery.parseJSON( data);
					$('#clientName').prop('readonly', true);
					$('#clientID').val( info['clientID']);
					$('#requestAllID').val(info['requestID']);
				  });
		}else{
			errorMessage= '<div class="error"> Please provide a client name	</div>';
			$('#clientName').parent().append(errorMessage);
		}
		
	}
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
	
	function showEditProduct(productID) {
		
			$('#popup_window').fadeIn(300).load(   '<?php echo site_url('/admin/requests/edit_requested_product_form_ajax'); ?>'+'/'+productID  , {}, 
			function () {
				$(this).removeClass('loading')
			});
	};
	$(document).on('click', 'a.edit_requested_product', function () {
			console.log( this );
			showEditProduct(  $(this).attr('id'));
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
	$(document).on('click', 'a.window_close', function () {
			hideWindow();
			return false
		});
	$(document).on('click', 'a#add_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/add_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#requested_products').append( data );
			  });
			return false
		});
		
	$(document).on('click', 'a#save_requested_product', function () {
			$.post( '<?php echo site_url('/admin/requests/save_requested_product_ajax'); ?>', $( "#requestedProductForm" ).serialize() )
				.done(function( data ) {
				hideWindow();
				$('#requested_product').html( data );
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
		
	$(document).on('click', '#drop a', function () {
			 $(this).parent().find('input').click();
			 
		});

	



    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

	

	
});


</script>



<h1 class="headingleft">Add Request </h1><a class="button icon-revert" href="<?php echo site_url('/admin/requests/view_my_open'); ?>">Back to my requests</a>









<br class="clear" />

<div id="details" class="tab">
	<?php if ($errors = validation_errors()): ?>
		<div class="error">
			<?php echo $errors; ?>
		</div>
	<?php endif; ?>	
	
	<div id='step1' class="container_12">

		<h2 class="underline">Client detail</h2>
	
		<p class="inline-label">
			<label for="clientName" class="label">Client name:</label>
			<?php echo $request['clientName']; ?>
		</p>
		<p class="inline-label">
			<label for="createdDate" class="label">Date Created:</label>
			<?php echo $request['dateCreated']; ?>
		</p>
		<p class="inline-label">
			<label for="createdDate" class="label">Creater:</label>
			<?php echo $request['repName']; ?>
		</p>
		
	</div>
	
	<div id="step2" class="container_12">
		<h2 class="underline">Requested Products detail</h2>
		
		<div id="requested_products" class="container_12" >
		
		
		<?php if ($requestedProducts): ?>
		<?php foreach($requestedProducts as $requestedProduct): ?>
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
				<label for="comment"  class="label">Comment:</label><span  class='fieldValue'> <?php echo   array_key_exists('comment', $requestedProduct )?$requestedProduct['comment']:'';?></span>
				
				</p>
				</div>
				<div class="grid_2">
					<span class="status-<?php echo strtolower($requestedProduct['status']);?>" ></span>
					<br/>
					
					<?php if ($requestedProduct['status'] !="O"  ): ?>
						<label for="accepter" class="label">Accepter:</label><?php echo  $requestedProduct['accepterName']; ?>
						<br/>
						<label for="accepter" class="label">Start at:</label><?php echo  $requestedProduct['dateAccepted']; ?>
						<br/>
						<?php if ($requestedProduct['status'] !="Q"  ): ?>
						<label for="accepter" class="label">Quoted at:</label><?php echo  $requestedProduct['dateQuoted']; ?>
						<br/>
						<?php endif; ?>
						<a id="<?php echo  site_url('/admin/requests/view_request').'/'.$requestedProduct['productID']; ?>" class="button icon-read view_requested_product">View Quotes</a>
						<br/>
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
						<a href="<?php echo  site_url('/admin/requests/release').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-trash delete_requested_product">Release</a>
						<br/>
						<a href="<?php echo  site_url('/admin/requests/view_request').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-trash delete_requested_product">Quote</a>
						<br/>
					<?php endif; ?>
					<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
						<a id="<?php echo  $requestedProduct['productID']; ?>" class="button icon-pencil edit_requested_product">Edit</a>
						<br/>
					<?php endif; ?>
				</div>
			</div>
			<h2 class="underline">&nbsp;</h2>
			
	
		<?php endforeach; ?>
		
		<?php else: ?>

		<p class="clear">There is no product requested yet.</p>

		<?php endif; ?>
		
		
		
			
		</div>
	
	
	</div>		
	
		<input type='hidden' name='requestAllID' id='requestAllID' value=''/>
		
	
		<p class="inline-label">
		<label for="productTypeID" class="label">Product Type:</label>
		<?php
			$options = array(
				'0' => 'Select One of Product types'
			);
			if ($productTypes):
				foreach ($productTypes as $productType):
					$options[$productType['productTypeID']] = $productType['typeName'];
				endforeach;
			endif;					
			echo @form_dropdown('productTypeID', $options, set_value('productTypeID',  '6'), 'id="productTypeID" class="input float-left"');
		?>	
		<a href="#" class="addproduct showWindow button float-left icon-plus"> Add Product</a>
		</p>
		
	
	
	

</div>

<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	


<div id="popup_window" class="loading" style="display: none;">

</div>