{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_12">
      <div class="breadcrumbs">
        <p><span class="bread-home"><a href="{site:url}">Home</a></span><span><a href="{page:baseUrl}/products">{product:category}</a></span>{product:title}</p>
      </div>
    </div>
    
  </div>
  <div class="container_12">
	<div class="grid_9">
	
    <div id='product_services' class="wrapper"> 
      
       {if errors}
			<div class="error">
				{errors}
			</div>
		{/if}
		{if message}
			<div class="message">
				{message}
			</div>
		{/if}			
		
		<form method="post" action="{page:baseUrl}/shop/cart/add" class="default">
		
			<div class="description">
		
				<input type="hidden" name="productID" value="{product:id}" />
				
				<h1>{product:title}</h1>

				
				
				<div class="grid_3">
				{if product:image-path}
				
					<p><a href="{product:image-path}" title="{product:title}" class="lightbox"><img src="{product:image-path}" alt="{product:title}" class="productpic" width="178" /></a></p>
		
				{else}
				
					<p><img src="{page:baseUrl}/static/images/nopicture.jpg" alt="Product image" class="productpic" /></p>

				{/if}
				</div>
				<div class="grid_5">
					{if product:subtitle}
				
					<h2>{product:subtitle}</h2>
					
					{/if}
					
					<label for="stock_status">Availability:</label>
					<span id="stock_status">{product:status}</span></br>
					<label for="price">Price:</label>
					<span id="price">{product:price}</span></br>
					<div>
					{product:variations}				
					</div>
				
					{if product:productIsAvailable }
						<input type="submit" value="Add to Cart" class="btns button-yellow button-small" />
					{/if}
					{if product:massageIsAvailable }
						<a href="{page:baseUrl}/shop/make_appointment/{product:id}" class="btns button-yellow button-small" />Make appointment</a>
					
					{/if}
				</div>
			</div>
			<div class="grid_9">
			<label for="description">Description:</label></br>
					<span id="description">	{product:body}	</span>
			</div>
			<div class="grid_9">
			<div class="separator"></div>
				<div id="reviews">
				
					<h3>Reviews</h3>

					{if product:reviews}
						
						{product:reviews}
						<div class="review {review:class}" id="review{review:id}">
							<div>By <strong>{review:author}</strong> <small>on {review:date}</small></div>											
							<div>{review:body}</div>
						</div>
						<div class="clear"></div>
						{/product:reviews}
						{pagination}
					{else}

						<p><small>There are currently no reviews</small></p>

					{/if}						

				</div>

				<div>
					<!-- <a href="{page:baseUrl}/shop/recommend/{product:id}" class="button-yellow button-small">Recommend this product</a> -->
					<a href="{page:baseUrl}/shop/review/{product:id}" class="button-yellow button-small">Write a review</a>						
				</div>					
						
			</div>
						
			
		</form>
      
    </div>
	

    </div>
	
     <div class="grid_3">
	
		<h3>Categories</h3>
	
		<ul class="menu">
			{shop:categories}
		</ul>
		
	</div>

	
      
  </div>
    <div class="container_12">
<div class="separator"></div>
</div>
</section>
	
	
{include:yuntest-footer}