
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
		<h3>Recent Requests</h3>
		
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
			<?php if ($recentRequests): $isOdd=TRUE; ?>
			<?php foreach($recentRequests as $request):$style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>			
			
				<tr class="<?php echo $style  ; ?>">
						<th scope="row" class="checkbox-cell  sorting_1"><?php echo $request['productID']; ?></th>
						<td class=" "><?php echo $request['clientName']; ?></td>
						<td class="hide-on-mobile "><?php echo $request['dateCreated']; ?></td>
						<td class="hide-on-mobile-portrait "><?php echo $request['productName']; ?></td>
						<td class="hide-on-tablet "> <span class="status-<?php echo strtolower($request['status']);?>" ></span> </td>
						<td class="low-padding align-center "><?php echo $request['accepterName']; ?></td>
						<td class="low-padding ">
							<span class="button-group compact">
								<?php if (in_array('requests', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'View', ' class="button icon-read with-tooltip" title="View Details"'); ?>
								<?php endif; ?>
										<!-- Sale team -->
							<?php if (in_array('requests_edit', $this->permission->permissions) &&  strtoupper($request['status']) =='A'): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'Edit',  'class="button icon-pencil with-tooltip" title="Edit this Request"'); ?>
							<?php endif; ?>						
							<?php if (in_array('requests_delete', $this->permission->permissions) &&  strtoupper($request['status']) =='A'): ?>
								<?php echo anchor('/admin/requests/delete/'.$request['productID'], 'Delete', 'class="button icon-trash with-tooltip confirm" title="Delete"  onclick="return confirm(\'Are you sure you want to delete this?\')"'); ?>
							<?php endif; ?>
							<!-- Source team -->
							<?php if (in_array('requests_accept', $this->permission->permissions) &&   strtoupper($request['status']) =='O' ): ?>
								<?php echo anchor('/admin/requests/accept/'.$request['productID'], 'Accept',  'class="button icon-inbox with-tooltip accept" title="Save this Request to my job list"'); ?>
							<?php endif; ?>
							
							<?php if (in_array('requests_release', $this->permission->permissions) &&   strtoupper($request['status']) =='A'  &&  $request['accepterID'] == $this->session->userdata('userID')  ): ?>
								<?php echo anchor('/admin/requests/release/'.$request['productID'], 'Release',  'class="button icon-outbox with-tooltip release" title="Release back this request"'); ?>
							<?php endif; ?>
							
							</span>
							
							
						
						</td>
				</tr>
	
			
				<?php endforeach; ?>
			<?php else: ?>
					<tr><td colspan='7'>No recent requests!</td></tr>
			<?php endif; ?>
			</tbody>
			
			</table>
		
		</div>
		<div id="">
		<h3>Recent Completed Requests</h3>
		<table class="table responsive-table responsive-table-on dataTable" id="sorting-advanced" aria-describedby="sorting-advanced_info">

				<thead>
					<tr role="row">
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateCreated','Date Created'); ?></th>
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateQuoted','Date Completed'); ?></th>
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Product name" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','productName','Product'); ?></th>
					
					<th scope="col" class="" role="columnheader" rowspan="1" colspan="1" aria-label="Job ID" width="60">Image</th>
					<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','creatorName','Sales Rep'); ?></th>
					
					
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Status" style="width: 60px;"><?php echo order_link('/admin/requests/view_my_summary','accpeterName','Sourcing Rep'); ?></th>
					<th scope="col" width="150" class="align-center sorting" role="columnheader" rowspan="1" colspan="1"  style="width:150px;">Actions</th>
					</tr>
					
				</thead>
				<tfoot>
			<tr><td colspan="7" rowspan="1">
					<p style="text-align: right;">
						<a href="<?php echo site_url('/admin/requests/viewall_archived'); ?>" class="button compact ">More...</a>
					</p>
			</td></tr>
			</tfoot>
			<tbody role="alert" aria-live="polite" aria-relevant="all">					
			<?php if ($recentCompletedRequests): $isOdd=TRUE; ?>
			<?php foreach($recentCompletedRequests as $request):$style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>			
			
				<tr class="<?php echo $style  ; ?>">
						<td class="hide-on-mobile "><?php echo $request['dateCreated']; ?></td>
						<td class="hide-on-mobile "><?php echo $request['dateQuoted']; ?></td>
						<td class="hide-on-mobile-portrait "><?php echo $request['productName']; ?></td>
						<th scope="row" class="checkbox-cell  sorting_1"><?php echo $request['imgs']; ?></th>
						<td class=" "><?php echo $request['repName']; ?></td>
						
						
						<td class="hide-on-tablet "><?php echo $request['accepterName']; ?></td>
						
						<td class="low-padding ">
							<span class="button-group compact">
								<?php if (in_array('requests', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'View', ' class="button icon-read with-tooltip" title="View Details"'); ?>
								<?php endif; ?>
							</span>
							
						</td>
				</tr>
	
			
			<?php endforeach; ?>
			<?php else: ?>
					<tr><td colspan='7'>No completed requests!</td></tr>
			<?php endif; ?>
			</tbody>
			
			</table>
		
		</div>
		<div id="">
		<h3>Recent Requests Accepted</h3>
		<table class="table responsive-table responsive-table-on dataTable" id="sorting-advanced" aria-describedby="sorting-advanced_info">

				<thead>
					<tr role="row">
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateAccepted','Date'); ?></th>
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Product name" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','productName','Product'); ?></th>
					
					
					<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','creatorName','Sales Rep'); ?></th>
					
						<th scope="col" width="15%" class="sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Customer" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','accepterName','Sourcing Rep'); ?></th>
					<th scope="col" width="150" class="align-center sorting" role="columnheader" rowspan="1" colspan="1"  style="width:150px;">Actions</th>
					</tr>
					
				</thead>
				<tfoot>
			<tr><td colspan="6" rowspan="1">
					<p style="text-align: right;">
						<a href="<?php echo site_url('/admin/requests/viewall_accepted'); ?>" class="button compact ">More...</a>
					</p>
			</td></tr>
			</tfoot>
			<tbody role="alert" aria-live="polite" aria-relevant="all">					
			<?php if ($recentAcceptedRequests): $isOdd=TRUE; ?>
			<?php foreach($recentAcceptedRequests as $request):$style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>			
			
				<tr class="<?php echo $style  ; ?>">
						<td class="hide-on-mobile "><?php echo $request['dateAccepted']; ?></td>
						<td class="hide-on-mobile-portrait "><?php echo $request['productName']; ?></td>
						<td class=" "><?php echo $request['repName']; ?></td>
						<td class=" "><?php echo $request['accepterName']; ?></td>
						
						<td class="low-padding ">
							<span class="button-group compact">
								<?php if (in_array('requests', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'View', ' class="button icon-read with-tooltip" title="View Details"'); ?>
								<?php endif; ?>
							
							<!-- Source team -->
					
							
							<?php if (in_array('requests_release', $this->permission->permissions)  &&  $request['accepterID'] == $this->session->userdata('userID')  ): ?>
								<?php echo anchor('/admin/requests/release/'.$request['productID'], 'Release',  'class="button icon-outbox with-tooltip release" title="Release back this request"'); ?>
							<?php endif; ?>
							</span>
							
						</td>
				</tr>
	
			
			<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan='5'>No request been taken!</td></tr>
			<?php endif; ?>
			</tbody>
			
			</table>
		</div>
		
		<br/>
		
	




		
		<br />
	
	</div>
	
	<br class="clear" />

</div>
