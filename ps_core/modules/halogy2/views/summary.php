
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

		<h1><strong><?php echo ($this->session->userdata('firstName')) ? ucfirst($this->session->userdata('firstName')) : $this->session->userdata('username'); ?>'s</strong> Summary</h1>
		
		<?php if ($errors = validation_errors()): ?>
			<div class="error">
				<?php echo $errors; ?>
			</div>
		<?php endif; ?>

		<?php if ($message): ?>
			<div class="message">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		
		<?php if ($recentOpenRequests): ?>
			<h3>Recent Open Requests</h3>
			
			<ul>	
			<?php foreach($recentOpenRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName']. $request['imageUrl'];  ?> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		
		
		<?php if ($recentOpenRequests): ?>
			<h3>Recent Accepted Requests</h3>
			
			<ul>	
			<?php foreach($recentAcceptedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName']. $request['imageUrl'];  ?> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		
		
		<?php if ($recentCompletedRequests): ?>
			<h3>Recent Completed Requests</h3>
			
			<ul>	
			<?php foreach($recentCompletedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName']. $request['imageUrl'];  ?> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		
		<br/>
		<?php if (@in_array('users', $this->permission->permissions)): ?>
		
			<div class="module last">
			
				<h2><strong>Manage Your Users</strong></h2>
			
				<p>See who's using your site or add administrators to help you run it.</p>
	
				<p><a href="<?php echo site_url('/admin/users'); ?>" class="button">Manage Users</a></p>
				
			</div>

		<?php endif; ?>
		
		<?php if (@in_array('requests', $this->permission->permissions)): ?>
			<div class="module last">
			
				<h2><strong>Requests</strong></h2>
			
				<p>Manage all opening and in processing requests .</p>
			
				<p><a href="<?php echo site_url('/admin/requests'); ?>" class="button">Manage Requests</a></p>
				
			</div>
			
		<?php endif; ?>
		<?php if (@in_array('requests', $this->permission->permissions)): ?>
			<div class="module last">
			
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
