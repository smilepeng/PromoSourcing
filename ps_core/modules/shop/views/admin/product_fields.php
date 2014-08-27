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

var fixHelper = function (e, a) {
	if ($(this).is('tbody')) {
		a.children().each(function () {
			$('table.order .' + $(this).attr('class')).width($(this).width())
		})
	}
	return a
};

function formatItem(row){
	if (row[0].length) return row[1]+'<br /><span >(#'+row[0]+')</span>';
	else return 'No results';
}
$(function(){
    $('#searchbox').fieldreplace();
	$('#searchbox').autocomplete("<?php echo site_url('/admin/shop/ac_product_fields'); ?>", { delay: "0", selectFirst: false, matchContains: true, formatItem: formatItem, minChars: 2 });
	$('#searchbox').result(function(event, data, formatted){
		$(this).parent('form').submit();
	});
	

});
</script>

<h1 class="headingleft">Product Features</h1>

<div class="headingright">

	<form method="post" action="<?php echo site_url('/admin/shop/product_fields'); ?>" class="default" id="search">
		<input type="text" name="searchbox" id="searchbox" class="formelement inactive" title="Search product features..." />
		<input type="image" src="<?php echo $this->config->item('staticPath'); ?>/images/btn_search.gif" id="searchbutton" />
	</form>
	
	



	<?php if (in_array('shop_edit', $this->permission->permissions)): ?>	
		<a href="<?php echo site_url('/admin/shop/add_product_field'); ?>" class="button">Add Product Feature</a>
	<?php endif; ?>
</div>

<div class="clear"></div>

<?php if ($product_fields): ?>

<?php echo $this->pagination->create_links(); ?>

<table class="default clear">
	<thead>
		<tr>
			<th><?php echo order_link('admin/shop/product_fields','fieldName','Feature Name',  4); ?></th>
			<th><?php echo order_link('admin/shop/product_fields','fieldSafe','Key Name',  4); ?></th>
			<th><?php echo order_link('admin/shop/product_fields','filedType','Type', 4  ); ?></th>
			<th><?php echo order_link('admin/shop/product_fields','defaultValue','Default', 4); ?></th>
			<th><?php echo order_link('admin/shop/product_fields','valueSet','Value Set', 4); ?></th>
			<th><?php echo order_link('admin/shop/product_fields','sampleValue','Sample',  4); ?></th>
			
			<th class="tiny">&nbsp;</th>		
		</tr>
	</thead>
	<tbody id="product_fields">
	<?php foreach ($product_fields as $product_field): ?>
		<tr class="" id="product_fields-<?php echo $product_field['fieldID']; ?>">
			<td class="col1"><?php echo (in_array('shop_edit', $this->permission->permissions)) ? anchor('/admin/shop/edit_product_field/'.$product_field['fieldID'], $product_field['fieldName']) : $product_field['fieldName']; ?></td>
			<td class="col2"><?php echo $product_field['fieldSafe']; ?></td>
			<td class="col3"><?php echo $product_field['fieldType']; ?></td>
			<td class="col4"><?php echo $product_field['defaultValue']; ?></td>
			<td class="col5"><?php echo $product_field['valueSet']; ?></td>
			<td class="col6"><?php echo $product_field['sampleValue']; ?></td>
			
			<td class="col6 tiny">
				<?php if (in_array('shop_edit', $this->permission->permissions)): ?>	
					<?php echo anchor('/admin/shop/edit_product_field/'.$product_field['fieldID'], 'Edit'); ?>
				<?php endif; ?>
			</td>
			
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php echo $this->pagination->create_links(); ?>

<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>

<?php else: ?>

<p>No product features were found.</p>


<?php endif; ?>

