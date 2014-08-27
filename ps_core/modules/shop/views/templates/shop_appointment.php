{include:yuntest-header}

<section id="content">
  <div class="container_12">
   
     <div class="grid_12">
      <div class="breadcrumbs">
        <p><span class="bread-home"><a href="{site:url}">Home</a></span><a href="{page:uri}">Appointment</a>Making Appointment</p>
      </div>
    </div>
    <div class="grid_9">
      <div class="block-ident-4 ">
	    
        <h2>Book an <strong>Appointment</strong></h2>
         <p class="text_big"> To book an appointment fill this form. One of {site:name} will respond within a day with a confirmation of your appointments. </p>
          
          <!--appointment starts-->
          <form  id="appointment-form" action="{site:url}index.php/shop/appointment/add" method="post"  class="default">

            <fieldset>
              <h5><strong>Appointment</strong> information &raquo; </h5>
              <br class="clear"/>
			   {if headlines:shop:category(massage):products } 
              <ul class="shop_form">
                <li>
                  <label>Massage Service</label>
                  <select name="package" class="select_style">
                   
                    {headlines:shop:category(massage):products}
                    <option value="{headline:id}">{headline:title} ({headline:price})</option>
                    {/headlines:shop:category(massage):products}
                   
                  </select>
                </li>
               
                <li class="datetrigger_wrapper">
                  <label>Preferred date*</label>
                  <input name="date1" id="datepicker1" class="required datebox" type="text" />
                </li>
                 <li class="omega">
                  <label>Prefered Time*</label>
                  <select name="time" class="select_style">
                    <option value="9:00 AM - 12:00 Noon">9:00 AM - 12:00 Noon</option>
                    <option value="12:00 Noon - 3:00 PM">12:00 Noon - 3:00 PM</option>
                    <option value="3:00 PM - 6:00 PM">3:00 PM - 6:00 PM</option>
                  </select>
                </li>
              </ul>
              <br class="clear"/>
              <h5><strong>Personal</strong> information &raquo; </h5>
              <br class="clear"/>
              <ul class="shop_form">
                <li>
                  <label>First Name* </label>
                  <input name="firstname" class="required" />
                </li>
                <li class="omega">
                  <label>Last Name </label>
                  <input name="lastname" />
                </li>
                <li>
                  <label>Email* </label>
                  <input name="email" class="required email"/>
                </li>
                <li class="omega">
                  <label>Phone </label>
                  <input name="phone" class="number"/>
                </li>
                <li>
                  <label>Message* </label>
                  <textarea name="message" id="appoint_message" class="required"></textarea>
                </li>
               
              </ul>
			  <input type="submit" name="checkout_appointment" class="button-yellow button-small" value="Book My Appointment"/>
			  
			   {else}
                    <div>No massage service is available.</div>
               {/if}
            </fieldset>
          </form>
          <!--appointment ends--> 
          
      </div>
    </div>
    	<div class="grid_3">
	
		<h3>Categories</h3>
	
		<ul class="menu">
			{shop:categories}
		</ul>
		
	</div>
	
     
     
  </div>

  
  </section>
<script type="text/javascript">

$(function(){
$('input.datebox').datepicker({dateformat:'dd M yy'});
});
function make_appointment(){
      
	var requiredFields = 'input#name, input#email';
		var success = true;
		$(requiredFields).each(function(){
			if (!$(this).val().trim().length) {
						
				$(this).next("span").show();				
				success = false;
			}else{
                              $(this).next("span").hide();
                        }  
		});
		if (success){
		
		  url= "{site:url}index.php/shop/add_appointment";
		  
		  data= {
			'name': $("#name").val(),
			'email': $("#email").val()
			
		  };
                 
                 
		 $.post(url, data)
                    .done(function(data) {
                       $( "#confirm").html(data );
                       })
  .fail(function(data) {
    $( "#confirm").html(data );
  });
              }
}
</script>
{include:yuntest-footer}