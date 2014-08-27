<script type="text/javascript">
	var days = <?php echo $days; ?>;
</script>
<script type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.flot.time.js"></script>
<!--[if IE]>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.flot.init.js"></script>
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
	
	<div class="col1">

		<h1><strong><?php echo ($this->session->userdata('firstName')) ? ucfirst($this->session->userdata('firstName')) : $this->session->userdata('username'); ?>'s</strong> Dashboard</h1>
		
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

		<ul class="dashboardnav">
			<li class="<?php echo ($days == 30) ? 'active' : ''; ?>"><a href="<?php echo site_url('/admin'); ?>">Last 30 Days</a></li>
			<li class="<?php echo ($days == 60) ? 'active' : ''; ?>"><a href="<?php echo site_url('/admin/dashboard/60'); ?>">Last 60 Days</a></li>
			<li class="<?php echo ($days == 90) ? 'active' : ''; ?>"><a href="<?php echo site_url('/admin/dashboard/90'); ?>">3 Months</a></li>
			<li><a href="<?php echo site_url('/admin/tracking'); ?>">Most Recent Visits</a></li>
		</ul>

		<div id="placeholder"></div>
		
		<div id="activity" class="loader">
			<?php echo $activity; ?>
		</div>

		
		
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
	
	<div class="col2">

		<h3>Site Info</h3>
		
		<table class="default">
			<tr>
				<th class="narrow">Site name:</th>
				<td><?php echo $this->site->config['siteName']; ?></td>
			</tr>
			<tr>
				<th class="narrow">Site URL:</th>
				<td><small><a href="<?php echo $this->site->config['siteURL']; ?>"><?php echo $this->site->config['siteURL']; ?></a></small></td>
			</tr>
			<tr>
				<th class="narrow">Site email:</th>
				<td><small><a href="mailto:<?php echo $this->site->config['siteEmail']; ?>"><?php echo $this->site->config['siteEmail']; ?></a></small></td>
			</tr>
		</table>

		<h3>Site Stats</h3>
		
		<table class="default">
			<tr>
				<th class="narrow">Disk space used:</th>
				<td><?php echo number_format($quota); ?> <small>KB</small></td>
			</tr>
			
		</table>

		<h3>User Stats</h3>
		
		<table class="default">
			<tr>
				<th class="narrow">Total users:</th>
				<td colspan="2"><?php echo number_format($numUsers); ?> <small>user<?php echo ($numUsers != 1) ? 's' : ''; ?></small></td>
			</tr>
			<tr>
				<th class="narrow">New today:</th>
				<td>			
					<?php echo number_format($numUsersToday); ?> <small>user<?php echo ($numUsersToday != 1) ? 's' : ''; ?></small>
				</td>
				<td>
					<?php
						$difference = 0;
						if ( $numUsersYesterday !=0  ){
							$difference = @round(100 / $numUsersYesterday * ($numUsersToday - $numUsersYesterday), 2);
						}
						$difference = 100;
						$polarity = ($difference < 0) ? '' : '+';
					?>						
					<?php if ($difference != 0): ?>
						<small>(<span style="color:<?php echo ($polarity == '+') ? 'green' : 'red'; ?>"><?php echo $polarity.$difference; ?>%</span>)</small>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th class="narrow">New yesterday:</th>
				<td colspan="2"><?php echo number_format($numUsersYesterday); ?> <small>user<?php echo ($numUsersYesterday != 1) ? 's' : ''; ?></small></td>
			</tr>
			<tr>
				<th class="narrow">New this week:</th>
				<td>
					<?php echo number_format($numUsersWeek); ?> <small>user<?php echo ($numUsersWeek != 1) ? 's' : ''; ?></small>
				</td>
				<td>
					<?php
						$difference = @round(100 / $numUsersLastWeek * ($numUsersWeek - $numUsersLastWeek), 2);
						$polarity = ($difference < 0) ? '' : '+';
					?>				
					<?php if ($difference != 0): ?>
						<small>(<span style="color:<?php echo ($polarity == '+') ? 'green' : 'red'; ?>"><?php echo $polarity.$difference; ?>%</span>)</small>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th class="narrow">New last week:</th>
				<td colspan="2"><?php echo number_format($numUsersLastWeek); ?> <small>user<?php echo ($numUsersLastWeek != 1) ? 's' : ''; ?></small></td>
			</tr>
		</table>	

		
		<br />
	
		
	</div>
	
	<br class="clear" />

</div>
