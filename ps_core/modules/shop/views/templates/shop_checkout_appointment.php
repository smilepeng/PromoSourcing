{include:yuntest-header}

<section id="content">
  <div class="container_12">
     <div class="grid_9">

		<h1>Checkout Appointment</h1>
		
		{if errors}
			<div class="error">
				{errors}
			</div>
		{/if}

		<p>Confirm your appointment information below is correct, then click on 'Proceed to Payment Page' to make payment. If you want to cancel your appointment click on the 'Cancel Appointment' button.</p>			
		
		<p>Your Appointment Information:</p>
	
		<table class="cart-table">
			<tr>
				<th></th>
				<th>Massage Service</th>
				<th>Date & time</th>
				<th width="80">Price ({site:currency})</th>
			</tr>
			{appointment:item}				
			<tr>
				<td colspan="4" ><hr /></td>
			</tr>
			{if appointment:discounts}
				<tr>
					<td colspan="2">Discounts applied:</td>
					<td>({appointment:discounts})</td>
				</tr>
			{/if}											
			<tr>
				<td colspan="3">Sub total:</td>
				<td>{cart:subtotal}</td>
			</tr>
			{if appointment:tax}
				<tr>
					<td colspan="3">Tax:</td>
					<td>{appointment:tax}</td>
				</tr>
			{/if}
			<tr>
				<td colspan="3"><strong>Total amount:</strong></td>
				<td><strong>{appointment:total}</strong></td>
			</tr>
			<tr>
				<td colspan="4" ><hr /></td>
			</tr>
		</table>
		
		
		<table class="checkout-address-table">
			<tr>
				<td valign="top">
					<h2>Massage Info</h2>
				
					<p>
						<strong>Massage name:</strong> {user:name}
						<br />
						
						<strong>Duration:</strong> {user:address1}
						<br />
					
						<strong>Start from:</strong> {user:address2}
						<br />
					
						<strong>Approximate End to:</strong> {user:address3}
						<br />
					
						<strong>Requirements:</strong> {user:city}
						<br />
					
					</p>
				</td>
				</tr>
			</table>
		
		<div style="float: right;">
			<p><a href="{site:url}index.php/shop/appointment" class="btns button-yellow button-small">Update Appointment</a></p>
		</div>
		
		<br class="clear" />
		
		<form action="{shop:gateway}" method="post" class="default">

			{shop:checkout}

			<div style="float:right">
				<a href="{site:url}index.php/shop/appointment/cancel" class="btns button-yellow button-small">Cancel Appointment</a>
				<input type="submit" value="Continue to Payment" style="font-weight: bold;" class="btns button-yellow button-small" />
			</div>
			<br class="clear" />

		</form>
	
		
		<!-- cards --><div><a href="#" onclick="javascript:window.open('https://www.paypal.com/uk/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img src="https://www.paypal.com/en_GB/GB/i/logo/PayPal_mark_37x23.gif" alt="Payments by Paypal"></a> <img src="{site:url}static/images/cards_visa.gif" alt="Visa Accepted" /> <img src="{site:url}static/images/cards_electron.gif" alt="Visa Electron Accepted" /> <img src="{site:url}static/images/cards_mastercard.gif" alt="Mastercard Accepted" /> <img src="{site:url}static/images/cards_visadelta.gif" alt="Visa Delta Accepted" /> <img src="{site:url}static/images/cards_switch.gif" alt="Switch Accepted" /> <img src="{site:url}static/images/cards_maestro.gif" alt="Maestro Accepted" /> <img src="{site:url}static/images/cards_solo.gif" alt="Solo Accepted" /></div>
				
		<p>Your appointment will be saved on file and you will receive an email confirmation containing your appointment details and reference number once the payment process is completed.</p>
		
		<p>For change appointment date and time, or Refund Procedure and other useful information please see our Terms and Conditions</a>.</p>
		

	</div>
	<div class="grid_3">
	
		{include:sidebar_account}
		
	</div>

     
  </div>
</section>
{include:yuntest-footer}