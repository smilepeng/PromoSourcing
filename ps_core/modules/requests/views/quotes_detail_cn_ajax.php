

<?php if ($quotes):  ?>
		<?php  $i=0; foreach($quotes as $quote): $i++;?>
					<div class="container_12">
				<div class="grid_3">
					<ul class="product_images" id="">

									<?php echo   array_key_exists('images', $quote )?$quote['images']:'';?>

					</ul>
				
				</div>
				<div class="grid_7">
				<p class="inline-label">
					<h2 >报价 <?php echo $i; ?></h2>
				</p>
					<p class="inline-label">
					<label for="productName" class="label">产品名称:</label> <span class='fieldValue'> <?php echo array_key_exists('productName', $quote )?$quote['productName']:'' ; ?> </span>
				</p>
				<?php echo $quote['quotedPrices']; ?>
				<p class="inline-label">
				
				<label for="setupCost" class="label">生产线安装费用:</label> <span  class='fieldValue'> <?php echo array_key_exists('setupCost', $quote )?$quote['setupCost']:'n/a' ; ?></span>
				</p>
				<p class="inline-label">
				
				<label for="sampleCost" class="label">样本费用:</label> <span  class='fieldValue'> <?php echo array_key_exists('sampleCost', $quote )?$quote['sampleCost']:'n/a' ; ?></span>
				</p>
				
				<p class="inline-label">

				<label for="setupTime" class="label">生产线安装用时:</label> <span  class='fieldValue'><?php echo  array_key_exists('setupTime-Weeks', $quote )?$quote['setupTime-Weeks']:'n/a';?> 周	</span>
				</p>
				<p class="inline-label">
				<label for="sampleTime" class="label">样品生产用时:</label> <span  class='fieldValue'><?php echo  array_key_exists('sampleTime-Weeks', $quote )?$quote['sampleTime-Weeks']:'n/a';?> 周</span>
				</p>
				<p class="inline-label">
				<label for="deliveryTime" class="label">送货用时:</label> <span  class='fieldValue'><?php echo  array_key_exists('deliveryTime-Weeks', $quote )?$quote['deliveryTime-Weeks']:'n/a';?> 周</span>
				</p>
				

				<?php echo $quote['quotedFeaturesDetail']; ?>
					
	
				<p class="inline-label">
				<label for="factoryDetail" class="label">工厂资料:</label> <span  class='fieldValue'><?php echo   array_key_exists('factoryDetail', $quote )?$quote['factoryDetail']:'n/a';?>	</span>
				</p>
				<p class="inline-label">		
				<label for="comment"  class="label">备注:</label><span  class='fieldValue'> <?php echo   array_key_exists('comment', $quote )?$quote['comment']:'';?></span>
				
				</p>
				</div>
				<div class="grid_2">

				
				<a  class="button icon-copy copy_quoted_product" id="<?php echo $quote['quotedProductID'];?>">复制该报价</a>
			
				</div>
			</div>
			<h2 class="underline">&nbsp;</h2>
			
	
		<?php endforeach; ?>
		
		<?php else: ?>

		<p class="clear">There is no quote yet.</p>

		<?php endif; ?>


