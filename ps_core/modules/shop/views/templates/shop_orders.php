{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">
		<h1>My Orders</h1>

		{if orders}
		
			{pagination}
			
			<table class="order-table">
				<tr>
					<th>Order ID</th>
					<th>Date</th>
					<th>Amount ({site:currency})</th>
					<th class="narrow">&nbsp;</th>
				</tr>
				{orders}
					<tr>
						<td>#{order:id}</td>
						<td>{order:date}</td>
						<td>{order:amount}</td>
						<td><a href="{order:link}" >View Order</a></td>
					</tr>
				{/orders}
			</table>

			{pagination}

		{else}

			<p>You have no orders yet.</p>

		{/if}

	</div>
	<div class="grid_3">
	
		{include:sidebar_account}
		
	</div>

</div>
	
   
  </div>
</section>
{include:yuntest-footer}