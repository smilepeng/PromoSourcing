{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_12">
      <div class="breadcrumbs">
        <p><span class="bread-home"><a href="{site:url}">Home</a></span><span><a href="{page:baseUrl}{category:parent:link}">{category:parent:title}</a></span>{page:heading}</p>
      </div>
    </div>
    
  </div>
  <div class="container_12">
     <div class="grid_9">

		<h1>{page:heading}</h1>
	
		{if shop:products}
		
			{pagination}
			<ul id="stage" class="portfolio-3column">

			{shop:products}
						
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
				   
		   {/shop:products}
	  
			</ul> 	
			{pagination}

		{else}

			       <P>No products found.</P>
		
		{/if}

	</div>
	<div class="grid_3">
	
		<h3>Categories</h3>
	
		<ul class="menu">
			{shop:categories}
		</ul>
		
	</div>

</div>
	
  </div>
</section>
{include:yuntest-footer}