{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">

		<h1>The Shop</h1>
		
		<h3>Featured Products</h3>
		
		{if shop:featured}
	
			<ul id="stage" class="portfolio-3column">

			{shop:featured}
						
			<li data-id="id-1" data-type="html">
			  <div class="img-border-port"><img src="{product:thumb-path}" class="img-border max-image" alt="" />
				<div class="cover boxcaption box_2">
				  <div class="project-name">
					<h5>{product:title}<span class="fright">{product:price}</span></h5> 
				  </div>
				  <div class="project-text">
					<p>{product:body}</p>
					<a href="{site:url}index.php{product:link}" class="button-yellow button-small">Detail</a> <span class="fleft">&nbsp; &nbsp;</span>
					<a href="javascript:add_to_cart('{product:id}');" class="btns button-yellow button-small">Add to Cart</a>   
					</div>
				</div>
			  </div>
			</li>
				   
		   {/shop:featured}
	  
			</ul> 	
	

		{else}

			<p><small>There are currently no featured products.</small></p>
		
		{/if}


		<h3>Popular Products</h3>

		{if shop:popular}
		<ul id="stage" class="portfolio-3column">

			{shop:popular}
						
			<li data-id="id-1" data-type="html">
			  <div class="img-border-port"><img src="{product:thumb-path}" class="img-border max-image" alt="" />
				<div class="cover boxcaption box_2">
				  <div class="project-name">
					<h5>{product:title}<span class="fright">{product:price}</span></h5> 
				  </div>
				  <div class="project-text">
					<p>{product:body}</p>
					<a href="{site:url}index.php{product:link}" class="button-yellow button-small">Detail</a> <span class="fleft">&nbsp; &nbsp;</span>
					<a href="javascript:add_to_cart('{product:id}');" class="btns button-yellow button-small">Add to Cart</a>   
					</div>
				</div>
			  </div>
			</li>
				   
		   {/shop:popular}
	  
			</ul> 	
		

		{else}

			<p><small>There are currently no products here.</small></p>
		
		{/if}

		<h3>Latest Products</h3>

		{if shop:latest}
		<ul id="stage" class="portfolio-3column">

			{shop:latest}
						
			<li data-id="id-1" data-type="html">
			  <div class="img-border-port"><img src="{product:thumb-path}" class="img-border max-image" alt="" />
				<div class="cover boxcaption box_2">
				  <div class="project-name">
					<h5>{product:title}<span class="fright">{product:price}</span></h5> 
				  </div>
				  <div class="project-text">
					<p>{product:body}</p>
					<a href="{site:url}index.php{product:link}" class="button-yellow button-small">Detail</a> <span class="fleft">&nbsp; &nbsp;</span>
					<a href="javascript:add_to_cart('{product:id}');" class="btns button-yellow button-small">Add to Cart</a>   
					</div>
				</div>
			  </div>
			</li>
				   
		   {/shop:latest}
	  
			</ul> 	
	

		{else}

			<p><small>There are currently no products here.</small></p>
		
		{/if}
		
		<h3>Most Viewed</h3>

		{if shop:mostviewed}
		<ul id="stage" class="portfolio-3column">

			{shop:mostviewed}
						
			<li data-id="id-1" data-type="html">
			  <div class="img-border-port"><img src="{product:thumb-path}" class="img-border max-image" alt="" />
				<div class="cover boxcaption box_2">
				  <div class="project-name">
					<h5>{product:title}<span class="fright">{product:price}</span></h5> 
				  </div>
				  <div class="project-text">
					<p>{product:body}</p>
					<a href="{site:url}index.php{product:link}" class="button-yellow button-small">Detail</a> <span class="fleft">&nbsp; &nbsp;</span>
					<a href="javascript:add_to_cart('{product:id}');" class="btns button-yellow button-small">Add to Cart</a>   
					</div>
				</div>
			  </div>
			</li>
				   
		   {/shop:mostviewed}
	  
			</ul> 	
	
		{else}

			<p><small>There are currently no products here.</small></p>
		
		{/if}

	</div>
	<div class="grid_3">
	
		<h3>Categories</h3>
	
		<ul class="menu">
			{shop:categories}
		</ul>
		
	</div>
  </div>
</section>
{include:yuntest-footer}