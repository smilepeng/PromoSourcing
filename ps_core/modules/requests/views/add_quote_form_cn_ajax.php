<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data"  id="addQuoteForm" name="addQuoteForm" class="default">
<a class="window_close" href="#"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">添加报价</span>

<div style="clear:both;"></div>

<div id="window_scroll">


<h2 class="">报价详情:</h2>
		
	<p class="inline-label">
	<label class="label" for="productName">产品名称:</label>
	<?php echo @form_input('productName',set_value('productName', array_key_exists('productName', $data )?$data['productName']:''), 'id="productName'.'" class="input float-left"'); ?>
	</p>
	<?php echo $unitPriceForm; ?>
	<p class="inline-label">
	<label class="label" for="setupCost" class='fieldName'>生产线安装费用:</label> <?php echo @form_input('setupCost',set_value('setupCost', array_key_exists('setupCost', $data )?$data['setupCost']:''), 'id="setupCost'.'" class="input float-left"'); ?>
	
	</p>
	<p class="inline-label">
	<label class="label" for="sampleCost" class='fieldName'>样本费用:</label> <?php echo @form_input('sampleCost',set_value('sampleCost', array_key_exists('sampleCost', $data )?$data['sampleCost']:''), 'id="sampleCost'.'" class="input float-left"'); ?>	
	</p>
	<p class="inline-label">
	<label class="label" for="setupTime" class='fieldName'>生产线安装用时:</label> <?php echo @form_input('setupTime-Weeks',set_value('setupTime-Weeks', array_key_exists('setupTime-Weeks', $data )?$data['setupTime-Weeks']:''), 'id="setupTime-Weeks'.'" class="input float-left"'); ?>周
	</p>
	<p class="inline-label">
	<label class="label" for="sampleTime" class='fieldName'>样品生产用时:</label> <?php echo @form_input('sampleTime-Weeks',set_value('sampleTime-Weeks', array_key_exists('sampleTime-Weeks', $data )?$data['sampleTime-Weeks']:''), 'id="sampleTime-Weeks'.'" class="input float-left"'); ?>周
	</p>
	<p class="inline-label">
	<label class="label" for="deliveryTime" class='fieldName'>送货用时:</label> <?php echo @form_input('deliveryTime-Weeks',set_value('deliveryTime-Weeks', array_key_exists('deliveryTime-Weeks', $data )?$data['deliveryTime-Weeks']:''), 'id="deliveryTime-Weeks'.'" class="input float-left"'); ?>周
	
	</p>

	
	
	
	<?php echo $featuresForm; ?>
	<p class="inline-label">
	<label class="label" for="factoryDetail" class='fieldName'>工厂资料:</label><?php

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
	<label class="label" for="comment" class='fieldName'>备注:</label><?php $factoryDetail = array(
              'name'        => 'comment',
              'id'          => 'comment',
              'value'       => array_key_exists('comment', $data )?$data['comment']:'',
              'rows'   => '4',
			  'class'  =>'input full-width float-left',        
            );
	echo @form_textarea( $factoryDetail); ?>
	
	</p>
	
	<input type="hidden" name="typeID" value="<?php echo  	  array_key_exists('typeID', $data )?$data['typeID']:'';?>" />
	<input type="hidden" name="requestedProductID" value="<?php echo  	  array_key_exists('requestedProductID', $data )?$data['requestedProductID']:'';?>" />

	<input type="hidden" name="imgs" value="<?php echo   array_key_exists('imgs', $data )?$data['imgs']:''; ?>" />
		</form>
		
	<form id="upload_quote"  method="post"  action="<?php echo site_url('admin/images/upload_image_ajax'); ?>" enctype="multipart/form-data">
	<p class="inline-label">
	
		<label for="image" class="label">样品图片:</label>
		<span id="drop" class="float-left" >
			<a class='button icon-paperclip'>上传图片</a><input type="file" name="image" multiple />
		</span>
		<ul >
					<!-- The file uploads will be shown here -->
		</ul>
		
	</p>
		
	
	
<h2 class="underline"></h2>


</div>

<div class="headingright">
	<a  class="button save " id="add_quoted_product">添加此报价</a>
</div>
</form>
<!-- The file uploads will be shown here -->
	
		

<script type="text/javascript">

var ul = $('#upload_quote ul');
    $('#upload_quote').fileupload({
		
       
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
	

