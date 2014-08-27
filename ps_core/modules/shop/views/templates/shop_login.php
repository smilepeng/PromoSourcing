{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">

		<h1>Login</h1>

		<p>You have shopped here before with the email <strong>{user:email}</strong>. Please enter your password below.</p>
		
		{if errors}
			<div class="error">
				{errors}
			</div>
		{/if}	
		
		<form action="{page:uri}" method="post" class="default">
						
			<input type="hidden" name="email" value="{user:email}" />
		
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" value="" class="formelement" />
		
			<input type="submit" id="login" name="login" value="Login" class="btns button-yellow button-small" />
			<br class="clear" />
			
		</form>

		<br />

		<h3><a href="{site:url}index.php/shop/forgotten"  class="button-yellow button-small">Forgotten your password?</a></h3>

		<p>That's ok, we can <a href="{site:url}index.php/shop/forgotten"  class="button-yellow button-small">reset it for you</a>.</p>

		<br />

		<h3><a href="{site:url}index.php/shop/create_account/checkout" class="button-yellow button-small">Want to create a new account?</a></h3>

		<p>Alternatively you can <a href="{site:url}index.php/shop/create_account/checkout" class="button-yellow button-small">create a new account</a> if you want to.</p>		

		<br />

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