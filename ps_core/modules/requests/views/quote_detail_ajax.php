
	<div class="grid_2">
		<ul class="product_images" id="">

		<?php echo   array_key_exists('images', $quote )?$quote['images']:'';?>
		
		</ul>
		
	</div>
	<div class="grid_4">
				<p class="inline-label">
					<label for="productName" class="label">Product name:</label> <span class='fieldValue'> <?php echo array_key_exists('productName', $quote )?$quote['productName']:'' ; ?> </span>
				</p>
				<?php echo $quote['quotedPrices']; ?>
				<p class="inline-label">
				
				<label for="setupCost" class="label">Setup Cost:</label> <span  class='fieldValue'> <?php echo array_key_exists('setupCost', $quote )?$quote['setupCost']:'n/a' ; ?></span>
				</p>
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
	<div class="grid_1">
	<a  class="button icon-copy edit_quoted_product" id="<?php echo $quote['quotedProductID'];?>">Edit</a>
	<a  class="button icon-copy copy_quoted_product" id="<?php echo $quote['quotedProductID'];?>">Copy</a>
	</div>
	


