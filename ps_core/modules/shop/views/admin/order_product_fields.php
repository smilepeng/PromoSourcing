<script type="text/javascript">
function setOrder(){
	$.post('<?php echo site_url('/admin/shop/order/product_fields'); ?>',$(this).sortable('serialize'),function(data){ });
};

function initOrder(el){
	$(el).sortable({ 
		axis: 'y',
	    revert: false, 
	    delay: '80',
	    opacity: '0.5',
	    update: setOrder
	});
};

$(function(){
	initOrder('ol.order');
});
</script>
<style type="text/css">
.ac_results { padding: 0px; border: 1px solid black; background-color: white; overflow: hidden; z-index: 99999; }
.ac_results ul { width: 100%; list-style-position: outside; list-style: none; padding: 0; margin: 0; }
.ac_results li { margin: 0px; padding: 2px 5px; cursor: default; display: block; font: menu; font-size: 12px; line-height: 16px; overflow: hidden; }
.ac_results li span.email { font-size: 10px; } 
.ac_loading { background: white url('<?php echo $this->config->item('staticPath'); ?>/images/loader.gif') right center no-repeat; }
.ac_odd { background-color: #eee; }
.ac_over { background-color: #0A246A; color: white; }
</style>

<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.fieldreplace.js"></script>
<script type="text/javascript">

</script>

<h1 class="headingleft">Product Features</h1>

	<?php if (in_array('shop_edit', $this->permission->permissions)): ?>	
		<a href="<?php echo site_url('/admin/shop/product_fields'); ?>" class="button">Back to Product Features</a>
	<?php endif; ?>
<?php if ($ordered_product_fields): ?>

<form method="post" action="<?php echo site_url('/admin/shop/product_fields'); ?>">

	<ol class="order">
	<?php foreach ($ordered_product_fields as $product_field): ?>
		<li id="product_fields-<?php echo  $product_field['product_fieldsID']; ?>" >
			<div class="col1">			
				<span><strong><?php echo $product_field['fieldName'] ; ?></strong></span>
				<small>(<?php echo $product_field['fieldSafe']; ?>)</small>
			</div>
			<div class="col2">&nbsp;</div>
			<div class="buttons">
				<a href="<?php echo site_url('/admin/shop/edit_product_field/'.$product_field['product_fieldsID']); ?>" ><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_edit.png" alt="Edit" /></a>
				
			</div>
			
		</li>
	<?php endforeach; ?>
	</ol>

</form>
<br/>
<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>

<?php else: ?>

<p>No product features were found.</p>


<?php endif; ?>

