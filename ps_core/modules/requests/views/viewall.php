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
$(function(){
    $('#searchbox').fieldreplace();
	function formatItem(row) {
		if (row[0].length) return row[1]+'<br /><span class="email">('+row[0]+')</span>';
		else return 'No results';
	}
	$('#searchbox').autocomplete("<?php echo site_url('/admin/requests/ac_requests'); ?>", { delay: "0", selectFirst: false, matchContains: true, formatItem: formatItem, minChars: 2 });
	$('#searchbox').result(function(event, data, formatted){
		$(this).parent('form').submit();
	});	
});
</script>

<h1 class="headingleft">Requests</h1>

<div class="headingright">

	<form method="post" action="<?php echo site_url('/admin/requests/viewall'); ?>" class="default" id="search">
		<input type="text" name="searchbox" id="searchbox" class="formelement inactive" title="Search Requests..." />
		<input type="image" src="<?php echo $this->config->item('staticPath'); ?>/images/btn_search.gif" id="searchbutton" />
	</form>

	
	<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
		<a href="<?php echo site_url('/admin/requests/add'); ?>" class="button">Add Request</a>
	<?php endif; ?>
</div>

<?php if ($requests): ?>

<?php echo $this->pagination->create_links(); ?>

<table class="default clear">
	<tr>
		<th><?php echo order_link('/admin/requests/viewall','dateCreated','Date'); ?></th>
		<th><?php echo order_link('/admin/requests/viewall','createrName','Sale Rep'); ?></th>
		<th><?php echo order_link('/admin/requests/viewall','productName','Product'); ?></th>
		<th><?php echo order_link('/admin/requests/viewall','imageName','Image'); ?></th>
		<th><?php echo order_link('/admin/requests/viewall','status','Status'); ?></th>
		<th><?php echo order_link('/admin/requests/viewall','accepterName','Assigned To'); ?></th>		
		<th class="tiny">&nbsp;</th>		
	</tr>
<?php foreach ($requests as $request): ?>
<?php 
	$class = '';
	if ( $request['status']=='A' ) $class = 'class="blue"';
	elseif ( $request['status']=='O' ) $class = 'class="orange"';

	$requestname = $request['requestname'];
	$requestlink = (in_array('requests_edit', $this->permission->permissions)) ? anchor('/admin/requests/edit/'.$request['requestID'], $request['productName']) : $productName;
	
?>
	<tr <?php echo $class; ?>>
		
		<td><?php echo dateFmt($request['dateCreated'], '', '', TRUE); ?></td>
		<td><?php echo trim($request['createrName']); ?></td>
		<td><?php echo trim($request['productName']); ?></td>
		<td><input type="image" src="<?php echo $this->config->item('staticPath'). "/images/".trim($request['imageName']) ;  ?>" /></td>
		<td><?php echo trim($request['status']); ?>		</td>
		<td><?php echo trim($request['accepterName']); ?></td>
		<td class="tiny">
			<?php if (in_array('requests', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/requests/view/'.$request['requestID'], 'View'); ?>
			<?php endif; ?>
			<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/requests/edit/'.$request['requestID'], 'Edit'); ?>
			<?php endif; ?>
		
			<?php if (in_array('requests_delete', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/requests/delete/'.$request['requestID'], 'Delete', 'onclick="return confirm(\'Are you sure you want to delete this?\')"'); ?>
			<?php endif; ?>
			<?php if (in_array('requests_accept', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/requests/accept/'.$request['requestID'], 'Accept', ''); ?>
			<?php endif; ?>
			<?php if (in_array('requests_release', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/requests/release/'.$request['requestID'], 'Release', ''); ?>
			<?php endif; ?>

		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->pagination->create_links(); ?>

<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>

<?php else: ?>

<p class="clear">No requests found.</p>

<?php endif; ?>

