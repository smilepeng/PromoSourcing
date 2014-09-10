


<div id="tpl-2col">
	
	<div class="">

		<h1  ><strong><?php echo ($this->session->userdata('firstName')) ? ucfirst($this->session->userdata('firstName')) : $this->session->userdata('username'); ?></strong> 的总汇</h1>

		
		<div >
		<h3 class='headingleft'>所有近期客户单</h3>
		
		<p style="text-align: right;">
		<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
				<a href="<?php echo site_url('/admin/requests/add'); ?>" class="button compact icon-plus">添加客户单</a>
			<?php endif; ?>
		</p>
		<div class="table-header">
		
		<span >
			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data" >
				<span>
				关键词:
				<input type="text" name="search" id="search" class="input mid-margin-left" title="Search Requests..." placeholder="Search Requests..." />
				</span>
				创建日期 从:
				<span class="input">			
					<span class="icon-calendar"></span>
					<input type="date" name="datecreated_from" id="datecreated_from" class="input-unstyled datepicker _gldp" value="">
				</span>
				到:
				<span class="input">
					<span class="icon-calendar"></span>
					<input type="date" name="datecreated_to" id="datecreated_to" class="input-unstyled datepicker _gldp" value="">
				</span>
				<?php
					$options = array(
						'' => '显示全部',
						'O' => '未接客户单',
						'A' => '处理中客户单',
						'Q' => '已报价客户单'
					);
									
					echo @form_dropdown('status', $options, set_value('status', ''), 'id="status" class="input"');
				?>	
				<a title='Refine or search' class='button icon-search with-tooltip' id="searchbutton" >Refine</a>
				<input type='hidden' id="order" name="order" value="asc" />
				<input type='hidden' id="page" name="page" value="1" />
				<input type='hidden' id="order_name" name="order_name" value="dateCreated" />
				
				
				
			</form>	
		</span>

		&nbsp;
		</div>
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
		
		<?php if ($requests): $isOdd=TRUE; ?>
			<tfoot>
			<tr><td colspan="7" rowspan="1">
					<?php echo $this->pagination->total_rows; ?> requests found.
			</td></tr>
			</tfoot>
			<tbody role="alert" aria-live="polite" aria-relevant="all">	
			<?php  foreach($requests as $request): $style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>	
				<tr class="<?php echo $style  ; ?>">
						<th scope="row" class="checkbox-cell  sorting_1"><?php echo $request['productID']; ?></th>
						<td class=" "><?php echo $request['clientName']; ?></td>
						<td class=" "><?php echo $request['dateCreated']; ?></td>
						<td class="-portrait "><?php echo $request['productName']; ?></td>
						<td class=" "><span class="status-<?php echo strtolower($request['status']);?>" ></span></td>
						<td class="low-padding align-center "><?php echo $request['accepterName']; ?></td>
						<td class="low-padding ">
							<span class="button-group compact">
								<?php if (in_array('requests', $this->permission->permissions)): ?>
								<?php echo anchor('/admin/requests/view_request/'.$request['productID'], 'View', ' class="button icon-read with-tooltip" title="View Details"'); ?>
								<?php endif; ?>
										<!-- Sale team -->
							<?php if (in_array('requests_edit', $this->permission->permissions) &&  strtoupper($request['status']) =='A'): ?>
								<?php echo anchor('/admin/requests/edit/'.$request['productID'], 'Edit',  'class="button icon-pencil with-tooltip" title="Edit this Request"'); ?>
							<?php endif; ?>						
							<?php if (in_array('requests_delete', $this->permission->permissions) &&  strtoupper($request['status']) =='A'): ?>
								<?php echo anchor('/admin/requests/delete/'.$request['productID'], 'Delete', 'class="button icon-trash with-tooltip confirm" title="Delete"  onclick="return confirm(\'Are you sure you want to delete this?\')"'); ?>
							<?php endif; ?>
							<!-- Source team -->
							<?php if (in_array('requests_accept', $this->permission->permissions)  &&   strtoupper($request['status']) =='O' ): ?>
								<?php echo anchor('/admin/requests/accept/'.$request['productID'], 'Accept',  'class="button icon-inbox with-tooltip accept" title="Save this Request to my job list"'); ?>
							<?php endif; ?>
							
							<?php if (in_array('requests_release', $this->permission->permissions) &&   strtoupper($request['status']) =='A'  &&  $request['accepterID'] == $this->session->userdata('userID') ): ?>
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
					<tr><td colspan="7" rowspan="1">
							No  request found.
					</td></tr>
			</tfoot>
			</table>
		<?php endif; ?>
		</div>
		<br/>
		<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>

		
		
		<br class="clear" /><br />
	




		
		<br />
	
	</div>
	
	<br class="clear" />

</div>
