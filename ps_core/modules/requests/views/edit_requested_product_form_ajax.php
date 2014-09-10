<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data"  id="requestedProductForm" name="requestedProductForm" class="default">
<a class="window_close" href="#"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Edit Request for product</span>

<div style="clear:both;"></div>

<div id="window_scroll">
	<p class="inline-label">
	<label for="productTypeID" class="label">Product  Type</label>
			<?php
				$options = array(
					'0' => 'Select One of Product types'
				);
				if ($productTypes):
					foreach ($productTypes as $productType):
						$options[$productType['productTypeID']] = $productType['typeName'];
					endforeach;
				endif;					
				echo @form_dropdown('typeID', $options, set_value('typeID',  array_key_exists('typeID', $data )?$data['typeID']:$options[0]), 'id="typeID" class="button-height float-left"');
			?>	
			
	</p>
	<p class="inline-label">
	
	<label for="productName" class="label">Product name:</label>
	<?php echo @form_input('productName',set_value('productName', array_key_exists('productName', $data )?$data['productName']:''), 'id="productName'.'" class="input half-width float-left"'); ?>
	</p>
	<p class="inline-label">
	<label for="orderQuantities" class="label">Order Quantities:</label>
	
	<?php echo @form_input('orderQuantities',set_value('orderQuantities', array_key_exists('orderQuantities', $data )?$data['orderQuantities']:''), 'id="orderQuantities'.'" class="input float-left small-margin-right"'); ?>
		<span class="info-spot float-left ">
			<span class="icon-info-round"></span>
			<span class="info-bubble">
				Separate each order quantity with comma, e.g. 1000,2000,3000
			</span>
		</span>
	
	</p>	
	<p class="inline-label">
	<label for="targetPriceAU" class="label">Target Price:</label>
	<?php echo @form_input('targetPriceAU',set_value('targetPriceAU', array_key_exists('targetPriceAU', $data )?$data['targetPriceAU']:''), 'id="targetPriceAU'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label for="requiredTime" class="label">Required Time:</label>
	<?php echo @form_input('requiredTime',set_value('requiredTime', array_key_exists('requiredTime', $data )?$data['requiredTime']:''), 'id="requiredTime'.'" class="input float-left"'); ?>
	</p>
	<?php echo $featuresForm; ?>

	<p class="inline-label">
	<label for="description" class="label">Description:</label>
		<?php
		$description = array(
              'name'        => 'description',
              'id'          => 'description',
              'value'       => array_key_exists('description', $data )?$data['description']:'',
              'rows'   => '4',
			  'class'  =>'input full-width float-left',        
            );
	echo @form_textarea( $description); ?>
	</p>
	
	<p class="inline-label">	
	<label for="comment" class="label">Customer Comment:</label>
	<?php
		$comment = array(
              'name'        => 'comment',
              'id'          => 'comment',
              'value'       => array_key_exists('comment', $data )?$data['comment']:'',
              'rows'   => '4',
			  'class'  =>'input full-width float-left',        
            );
	echo @form_textarea( $comment); ?>
	
	</p>
	<p class="inline-label">
	<label for="tags" class="label">Key words:</label>
	<?php echo @form_input('tags',set_value('tags', array_key_exists('tags', $data )?$data['tags']:''), 'id="tags'.'" class="input float-left"'); ?>
	</p>
	<input type="hidden" name="requestID" value="<?php echo  	  array_key_exists('requestID', $data )?$data['requestID']:'';?>" />
	<input type="hidden" name="productID" value="<?php echo   array_key_exists('productID', $data )?$data['productID']:''; ?>" />
	<input type="hidden" name="imgs" id="imgs" class="input half-width float-left" value="<?php echo   array_key_exists('imgs', $data )?$data['imgs']:''; ?>" />
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


</div>

<div class="headingright">
	<a  class="button save " id="edit_requested_product">Save Changes</a>
</div>

<script type="text/javascript">

	var ul = $('#upload ul');
    $('#upload').fileupload({
		
        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
			
            var tpl = $('<li class="working"><p></p><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><span></span></li>');

			var reader = new FileReader();
            imagefile=data.files[0];
            reader.onload = function (e) {
				
               var li = '<img class="img-preview" src="' + e.target.result + '" \
               title="'+ escape(imagefile.name) +'" /> \
               ' + escape(imagefile.name) +
               ' Size: '+ imagefile.size + ' b';
              
               tpl.find('p').html(li);
			};
                    
               
			reader.readAsDataURL(data.files[0]);
            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },
		dataType: 'json',
		done :function (e, data) {
            
			imgs= $('#imgs').val()+ ( $('#imgs').val().length===0 ? '':',')+ data.result['imgRef'];
                $('#imgs').val(imgs);
            
        },
        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


</script>
<!-- The file uploads will be shown here -->
	

