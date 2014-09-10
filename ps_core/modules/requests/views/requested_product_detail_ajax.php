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
					<label for="accepter" class="label">Creater:</label><?php echo  $requestedProduct['repName']; ?>
						<br/>
						<label for="accepter" class="label">Created:</label><?php echo  $requestedProduct['dateCreated']; ?>
						<br/>
					<?php if ($requestedProduct['status'] !="O"  ): ?>
						
						<label for="accepter" class="label">Accepter:</label><?php echo  $requestedProduct['accepterName']; ?>
						<br/>
						<label for="accepter" class="label">Start at:</label><?php echo  $requestedProduct['dateAccepted']; ?>
						<br/>
						<?php if ($requestedProduct['status'] =="Q"  ): ?>
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
						<a href="<?php echo  site_url('/admin/requests/release').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-outbox release_requested_product">Release</a>
						<br/>
						<a href="<?php echo  site_url('/admin/requests/view_request').'/'.$requestedProduct['productID']; ?>" id="" class="button icon-list-add">Quote</a>
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
			<h2 class="underline">&nbsp;</h2>