
<script type="text/javascript">
function refresh(){
	$('div.loader').load('<?php echo site_url('/admin/activity_ajax'); ?>');
	timeoutID = setTimeout(refresh, 5000);
}
$(function(){
	timeoutID = setTimeout(refresh, 5000);
});
</script>

<div id="tpl-2col">
	
	<div class="">

		<h1>System Summary</h1>
		

		
		<div id="">
		<h3>Recent Open Requests</h3>
		
			<table class="table responsive-table responsive-table-on dataTable" id="sorting-advanced" aria-describedby="sorting-advanced_info">

				<thead>
					<tr role="row">
					<th scope="col" class="sorting" role="columnheader" rowspan="1" colspan="1" aria-label="Job ID" width="60"><?php echo order_link('/admin/requests/view_my_summary','productID','Job ID'); ?></th>
					<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','clientName','Customer'); ?></th>
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateCreated','Date'); ?></th>
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Product name" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','productName','Product'); ?></th>
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Status" style="width: 60px;"><?php echo order_link('/admin/requests/view_my_summary','status','Status'); ?></th>
					<th scope="col" width="60" class="align-center sorting" role="columnheader" rowspan="1" colspan="1" aria-label="Assigned" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','accepterName','Assigned to'); ?></th>
					<th scope="col" width="150" class="align-center sorting" role="columnheader" rowspan="1" colspan="1"  style="width:150px;">Actions</th>
					</tr>
					
				</thead>
			<tbody role="alert" aria-live="polite" aria-relevant="all">					
			<?php if ($recentOpenRequests): $isOdd=TRUE; ?>
			<?php foreach($recentOpenRequests as $request):$style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>			
			
				<tr class="<?php echo $style  ; ?>">
						<th scope="row" class="checkbox-cell  sorting_1"><?php echo $request['productID']; ?></th>
						<td class=" "><?php echo $request['clientName']; ?></td>
						<td class="hide-on-mobile "><?php echo $request['dateCreated']; ?></td>
						<td class="hide-on-mobile-portrait "><?php echo $request['productName']; ?></td>
						<td class="hide-on-tablet "><?php echo $request['status']; ?></td>
						<td class="low-padding align-center "><?php echo $request['accepterName']; ?></td>
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
		<?php endif; ?>
		</div>
		<div id="activity">
		<h3>Recent Accepted Requests</h3>
		
		<?php if ($recentAcceptedRequests): ?>
			
			
			<ul>	
			<?php foreach($recentAcceptedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName']. $request['imageUrl'];  ?> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		</div>
		<div id="activity">
		<h3>Recent Completed Requests</h3>
		<?php if ($recentCompletedRequests): ?>
			<ul>	
			<?php foreach($recentCompletedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName']. $request['imageUrl'];  ?> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		</div>
		
		<br/>
		<?php if (@in_array('users', $this->permission->permissions)): ?>
		
			<div class="module ">
			
				<h2><strong>Manage Your Users</strong></h2>
			
				<p>See who's using your site or add administrators to help you run it.</p>
	
				<p><a href="<?php echo site_url('/admin/users'); ?>" class="button">Manage Users</a></p>
				
			</div>

		<?php endif; ?>
		
		<?php if (@in_array('requests', $this->permission->permissions)): ?>
			<div class="module ">
			
				<h2><strong>Requests</strong></h2>
			
				<p>Manage all opening and in processing requests .</p>
			
				<p><a href="<?php echo site_url('/admin/requests'); ?>" class="button">Manage Requests</a></p>
				
			</div>
			
		<?php endif; ?>
		<?php if (@in_array('requests', $this->permission->permissions)): ?>
			<div class="module ">
			
				<h2><strong>Quote History</strong></h2>
			
				<p>View and search requests or quotes in history.</p>
			
				<p><a href="<?php echo site_url('/admin/archive'); ?>" class="button">Manage Archives</a></p>
				
			</div>
			
		<?php endif; ?>
		<br class="clear" /><br />
	




		
		<br />
	
	</div>
	
	<br class="clear" />

</div>
