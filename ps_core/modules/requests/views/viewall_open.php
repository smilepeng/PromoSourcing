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
		if (row[0].length) return row[0];
		else return 'No results';
	}
	$('#searchbox').autocomplete("<?php echo site_url('/admin/requests/ac_requests'); ?>", { delay: "0", selectFirst: false, matchContains: true, formatItem: formatItem, minChars: 2 });
	$('#searchbox').result(function(event, data, formatted){
		$(this).parent('form').submit();
	});	
});
</script>

<h1 class="headingleft">All Open Requests</h1>
<br/>

		<div >
		
		<p style="text-align: right;">
		<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
				<a href="<?php echo site_url('/admin/requests/add'); ?>" class="button compact icon-plus">Add Request</a>
			<?php endif; ?>
		</p>
		<div class="table-header button-height">
		
		<span >
			<span>
			Search&nbsp;
			<input type="text" name="table_search" id="table_search" class="input mid-margin-left" title="Search Requests..." placeholder="Search Requests..." />
			</span>
			
			<a title='Refine or search' class='button icon-search with-tooltip' id="searchbutton" >Refine</a>
			
		</span>

		&nbsp;
		</div>
		<table class="table responsive-table responsive-table-on dataTable" id="sorting-advanced" aria-describedby="sorting-advanced_info">

				<thead>
					<tr role="row">
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateCreated','Date'); ?></th>
					<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','creatorName','Sales Rep'); ?></th>
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Product name" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','productName','Product'); ?></th>
					<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;">Image</th>
					
				


					<th scope="col" width="150" class="align-center sorting" role="columnheader" rowspan="1" colspan="1"  style="width:150px;">Actions</th>
					</tr>
					
				</thead>
		
		<?php if ($requests): $isOdd=TRUE; ?>
			<tfoot>
			<tr><td colspan="5" rowspan="1">
					<?php echo $this->pagination->total_rows; ?> requests found.
			</td></tr>
			</tfoot>
			<tbody role="alert" aria-live="polite" aria-relevant="all">	
			<?php  foreach($requests as $request): $style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>	
				<tr class="<?php echo $style  ; ?>">
						<td class="hide-on-mobile "><?php echo $request['dateCreated']; ?></td>
						<td class=" "><?php echo $request['repName']; ?></td>
						
						<td class="hide-on-mobile-portrait "><?php echo $request['productName']; ?></td>
						<td class=" "><?php echo $request['imgs']; ?></td>
						<td class="low-padding ">
							<span class="button-group compact">
								<?php if (in_array('requests', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'View', ' class="button icon-read with-tooltip" title="View Details"'); ?>
								<?php endif; ?>
										<!-- Sale team -->
							<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/edit/'.$request['productID'], 'Edit',  'class="button icon-pencil with-tooltip" title="Edit this Request"'); ?>
							<?php endif; ?>						
							<?php if (in_array('requests_delete', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/delete/'.$request['productID'], 'Delete', 'class="button icon-trash with-tooltip confirm" title="Delete"  onclick="return confirm(\'Are you sure you want to delete this?\')"'); ?>
							<?php endif; ?>
							<!-- Source team -->
							<?php if (in_array('requests_accept', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/accept/'.$request['productID'], 'Accept',  'class="button icon-inbox with-tooltip accept" title="Save this Request to my job list"'); ?>
							<?php endif; ?>
							
							<?php if (in_array('requests_release', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/release/'.$request['productID'], 'Release',  'class="button icon-outbox with-tooltip release" title="Release back this request"'); ?>
							<?php endif; ?>
							
							</span>
							
							
						
						</td>
				</tr>
				
			<?php endforeach; ?>
			</tbody>
			</table>
			<div class="table-footer large-margin-bottom">
				<?php    echo $this->pagination->create_links(); ?>
				&nbsp;
				
			</div>
			
		<?php else: ?>
			<tfoot>
					<tr><td colspan="6" rowspan="1">
							No  request found.
					</td></tr>
			</tfoot>
			</table>
		<?php endif; ?>
		</div>
		<br/>
		<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>





