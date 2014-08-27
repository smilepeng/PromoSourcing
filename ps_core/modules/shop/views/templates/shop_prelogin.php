{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">

		<h1>Have you shopped here before?</h1>
		
		<p>We can find out if you are have shopped here before if you just enter your email below.</p>
		
		<br />
		
		<form action="{page:uri}" method="post" class="default">
			<div id="account_info">			
			<label for="email">Email address:</label>
			<input type="text" id="email" name="email" value="" class="formelement" />
			<br class="clear" /><br />
		    </div>
			<input type="submit" id="login" name="login" value="Next Step &gt;" class="btns button-yellow button-small" />
			
		</form>
		
		<br class="clear" /><br />

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