{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">

		<h1>Shopping Cart</h1>
		
		{if errors}
			<div class="error">
				{errors}
			</div>
		{/if}		
		
		<form action="{site:url}index.php/shop/cart/update" method="post" id="cart_form" class="default">
		    <div id='cart-checkout'>
			<p>Your shopping cart contains:</p>
		
			<table class="cart-table">
				<tr>
					<th></th>
					<th>Product</th>
					<th>Quantity</th>
					<th width="80">Price ({site:currency})</th>
				</tr>
					{if cart:items}
						{cart:items}				
					{else}
						<tr>
							<td colspan="4">Your cart is empty!</td>
						</tr>
					{/if}
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>
					{if cart:discounts}
						<tr>
							<td colspan="3">Discounts applied:</td>
							<td>({cart:discounts})</td>
						</tr>
					{/if}
					<tr>
						<td colspan="3">Sub total:</td>
						<td>{cart:subtotal}</td>
					</tr>
					<tr>
						<td colspan="3">Shipping:</td>
						<td>{cart:postage}</td>
					</tr>
					{if cart:tax}
						<tr>
							<td colspan="3">Tax:</td>
							<td>{cart:tax}</td>
						</tr>
					{/if}
					<tr>
						<td colspan="3"><strong>Total amount:</strong></td>
						<td><strong>{cart:total}</strong></td>
					</tr>					
					<tr>
						<td colspan="4" ><hr /></td>
					</tr>					
			</table>
	
			<label for="shippingBand">Shipping Band:</label>
			<select name="shippingBand" id="shippingBand" onchange="document.getElementById('cart_form').submit();" class="formelement">
				{cart:bands}
			</select>
			<br class="clear" /><br />

			{if cart:modifiers}
		
				<label for="shippingModifier">Shipping Modifier:</label>
				<select name="shippingModifier" id="shippingModifier" onchange="document.getElementById('cart_form').submit();" class="formelement">
					{cart:modifiers}
				</select>
				<br class="clear" /><br />
				
			{/if}

			<label for="discountCode">Discount Code:</label>
			<input type="text" name="discountCode" id="discountCode" value="{form:discount-code}" class="formelement small" />
			<br class="clear"><br />

			</div>
			{if cart:items}
		
				<div style="float:right;">
					<input type="submit" value="Update Cart" class="btns button-yellow button-small" />
					<input name="checkout" type="submit" value="Checkout &gt;&gt;" class="btns button-yellow button-small" />
				</div>
				<br class="clear" />					

			{/if}
	
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