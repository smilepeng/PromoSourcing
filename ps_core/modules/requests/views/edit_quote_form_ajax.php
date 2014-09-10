<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data"  id="editQuoteForm" name="editQuoteForm" class="default">
<a class="window_close" href="#"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Edit Quote</span>

<div style="clear:both;"></div>

<div id="window_scroll">


<h2 class="">Quote:</h2>
		
	<p class="inline-label">
	<label class="label" for="productName">Product name:</label>
	<?php echo @form_input('productName',set_value('productName', array_key_exists('productName', $data )?$data['productName']:''), 'id="productName'.'" class="input float-left"'); ?>
	</p>
	<?php echo $unitPriceForm; ?>
	<p class="inline-label">
	<label class="label" for="setupTime" class='fieldName'>Setup Cost:</label> <?php echo @form_input('setupCost',set_value('setupCost', array_key_exists('setupCost', $data )?$data['setupCost']:''), 'id="setupCost'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label class="label" for="sampleCost" class='fieldName'>Sample Cost:</label> <?php echo @form_input('sampleCost',set_value('sampleCost', array_key_exists('sampleCost', $data )?$data['sampleCost']:''), 'id="sampleCost'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label class="label" for="setupTime" class='fieldName'>Setup Time:</label> <?php echo @form_input('setupTime-Weeks',set_value('setupTime-Weeks', array_key_exists('setupTime-Weeks', $data )?$data['setupTime-Weeks']:''), 'id="setupTime-Weeks'.'" class="input float-left"'); ?>Weeks
	</p>
	<p class="inline-label">
	<label class="label" for="sampleTime" class='fieldName'>Sample Time:</label> <?php echo @form_input('sampleTime-Weeks',set_value('sampleTime-Weeks', array_key_exists('sampleTime-Weeks', $data )?$data['sampleTime-Weeks']:''), 'id="sampleTime-Weeks'.'" class="input float-left"'); ?>Weeks
	</p>
	<p class="inline-label">
	<label class="label" for="deliveryTime" class='fieldName'>Delivery Time:</label> <?php echo @form_input('deliveryTime-Weeks',set_value('deliveryTime-Weeks', array_key_exists('deliveryTime-Weeks', $data )?$data['deliveryTime-Weeks']:''), 'id="deliveryTime-Weeks'.'" class="input float-left"'); ?>Weeks
	</p>
	
	<?php echo $featuresForm; ?>
	<p class="inline-label">
	<label class="label" for="factoryDetail" class='fieldName'>Factory Detail:</label><?php

		$factoryDetail = array(
              'name'        => 'factoryDetail',
              'id'          => 'factoryDetail',
              'value'       => array_key_exists('factoryDetail', $data )?$data['factoryDetail']:'',
              'rows'   => '4',
			  'class'  =>'input full-width float-left',        
            );
	echo @form_textarea( $factoryDetail); ?>
	</p>
	<p class="inline-label">		
	<label class="label" for="comment" class='fieldName'>Comment:</label>
		<?php $comment = array(
              'name'        => 'comment',
              'id'          => 'comment',
              'value'       => array_key_exists('comment', $data )?$data['comment']:'',
              'rows'   => '4',
			  'class'  =>'input full-width float-left',        
            );
	echo @form_textarea( $comment); ?>
	
	</p>
	<input type="hidden" name="typeID" value="<?php echo  	  array_key_exists('productTypeID', $data )?$data['productTypeID']:'';?>" />
	<input type="hidden" name="requestedProductID" value="<?php echo  	  array_key_exists('requestedProductID', $data )?$data['requestedProductID']:'';?>" />
	<input type="hidden" name="quotedProductID" value="<?php echo   array_key_exists('quotedProductID', $data )?$data['quotedProductID']:''; ?>" />
	<input type="hidden" name="imgs" value="<?php echo   array_key_exists('imgs', $data )?$data['imgs']:''; ?>" />	
	
	</form>
	<form id="upload"  method="post"  action="<?php echo site_url('admin/images/upload_image_ajax'); ?>" enctype="multipart/form-data">
	<p class="inline-label">
		<label for="image" class="label">Image:</label>
		<span id="drop" class="float-left" >
			<a class='button icon-paperclip'>Upload Image</a><input type="file" name="image" multiple />
		</span>
		<ul >
					<!-- The file uploads will be shown here -->
		</ul>
	</p>
		
	</form>

	<h2 class="underline"></h2>
	<?php echo   array_key_exists('images', $data )?$data['images']:''; ?>

</div>

<div class="headingright">
	<a  class="button save " id="save_quoted_product">Save Changes</a>
</div>
</form>
<!-- The file uploads will be shown here -->
	
		


