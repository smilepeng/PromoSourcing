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
		finishUrl = "<?php echo site_url('/admin/requests/view_client_ajax'); ?>"; 
		if( clientName.length > 0){
			$('a.addClient').addClass('loading');
			console.log($( "#clientDetail" ).serialize());
			$.post( '<?php echo site_url('/admin/requests/view_client_request'); ?>', $( "#clientDetail" ).serialize() ).done(function( data ) {
					$('a.addClient').fadeOut();
					$('#step2').fadeIn();
					info= jQuery.parseJSON( data);
					$('#finish').attr('href', finishUrl+'/'+info['requestID'])
					console.log(data);
					
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



<h1 class="headingleft">Add Request </h1> <span class="headingright"> <a class="button icon-revert" href="<?php echo site_url('/admin/requests/view_my_open'); ?>">Back to my requests</a></span>









<br class="clear" />

<div id="details" class="tab">
	<?php if ($errors = validation_errors()): ?>
		<div class="error">
			<?php echo $errors; ?>
		</div>
	<?php endif; ?>	
	
	<div id='step1' class="container_12">

<h2 class="underline">Step 1: Add Client detail</h2>
	
	<form name="clientDetail" id="clientDetail" method="post" action="<?php echo site_url('/admin/requests/add_client_ajax'); ?>" enctype="multipart/form-data" class="default">
	<input type='hidden' name='clientID' id='clientID' value=''/>
		<p class="inline-label">
			<label for="clientName" class="label">Client name:</label>
			<?php echo @form_input('clientName',set_value('clientName', array_key_exists('clientName', $data )?$data['clientName']:''), 'id="clientName" class="input float-left"'); ?>  <a href="#" class="button addClient"> Add </a>
		</p>

	
	</form>

	</div>
	<div id="step2" style='display:none' class="container_12">
		<input type='hidden' name='requestAllID' id='requestAllID' value=''/>
		<h2 class="underline">Step 2: Add Product</h2>
		
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
			echo @form_dropdown('productTypeID', $options, set_value('productTypeID',  array_key_exists('productTypeID', $data )?$data['productTypeID']:$options[0]), 'id="productTypeID" class="input float-left"');
		?>	
		<a href="#" class="addproduct showWindow button float-left icon-plus"> Add Product</a> 
		 <a href="javascript:viewClientRequest();" id="finish" class="button float-right">Finish</a>
		
		</p>
		
		<br/>
		
	</div>
	<div id="requested_products" class="container_12" >
		<!-- Load added product detail here -->
		
	</div>
	
	
	
	

</div>


<p class="clear" style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>
	


<div id="popup_window" class="loading" style="display: none;">

</div>