{include:yuntest-header}

<section id="content">
	 <div class="container_12">
     <div class="grid_12">
      <div class="breadcrumbs">
        <p><span class="bread-home"><a href="{site:url}">Home</a></span><span><a href="{page:baseUrl}/products">Products</a></span><span><a href="{product:link}">{product:title}</a></span></p>
      </div>
    </div>
    
  </div>
  <div class="container_12">
     <div class="grid_9">
<h1>Review Product</h1>

{if errors}
	<div class="error">
		{errors}
	</div>
{/if}		

<form method="post" action="{page:baseUrl}/shop/review/{product:id}" class="default" id="reviewsform">
    <div class="shop_form">
	<label for="fullName">Your Name</label>
	<input type="text" name="fullName" value="{form:name}" id="fullName" class="formelement" />
	<br class="clear" />

	<label for="email">Your Email</label>
	<input type="text" name="email" value="{form:email}" id="email" class="formelement" />
	<br class="clear" />

	<label for="rating">Rating</label>
	<select name="rating" class="formelement">
		<option value="1">1 / 5</option>
		<option value="2">2 / 5</option>
		<option value="3">3 / 5</option>
		<option value="4">4 / 5</option>
		<option value="5">5 / 5</option>																
	</select>
	<br class="clear" />

	<label for="reviewform">Review</label>
	<textarea name="review" id="reviewform" class="formelement small">{form:review}</textarea>
	<br class="clear" /><br />
    </div>
	<input type="submit" value="Post Review" class="btns button-yellow button-small" />
	<br class="clear" />

</form>

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