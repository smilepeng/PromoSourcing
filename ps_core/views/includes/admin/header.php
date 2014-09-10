<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/reset.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/colors.css" media="all" />

	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/table.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/form.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/login.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/skeleton.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/files.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/admin.css" media="all" />

	<link rel="icon" href="<?php echo $this->config->item('staticPath'); ?>/images/favicon.ico" type="image/x-icon" />	
	
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/modernizr.custom.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.ui.js"></script>
	
	<title><?php echo (isset($this->site->config['siteName'])) ? $this->site->config['siteName'] : 'Login to'; ?> Admin - Promo Sourcing</title>
	
</head>
<body>

<div class="bg">
	
	<div class="container">
	
		<div id="header">

			<div id="logo">
			
				<?php
					// set logo
					if ($this->config->item('logoPath')) $logo = $this->config->item('logoPath');
					elseif ($image = $this->uploads->load_image('admin-logo')) $logo = $image['src'];
					else $logo = $this->config->item('staticPath').'/images/ps_logo.png';
				?>

				<h1><a href="<?php echo site_url('/admin'); ?>"><?php echo (isset($this->site->config['siteName'])) ? $this->site->config['siteName'] : 'Login to'; ?> Admin</a></h1>
				<a href="<?php echo site_url('/admin'); ?>"><img src="<?php echo $logo; ?>" alt="Logo" /></a>

			</div>

			<div id="siteinfo">
				<ul id="toolbar">
							
					<?php if ($this->session->userdata('session_user')): ?>				
						
						<li><a href="<?php echo site_url('/admin/users/edit/'.$this->session->userdata('userID')); ?>">My Account</a></li>
						
						<?php if ($this->session->userdata('groupID') < 0 && @file_exists(APPPATH.'modules/halogy/controllers/halogy.php')): ?>
							<li class="noborder"><a href="<?php echo site_url('/admin/logout'); ?>">Logout</a></li>
							
						<?php else: ?>
							<li class="last"><a href="<?php echo site_url('/admin/logout'); ?>">Logout</a></li>
						<?php endif; ?>						
					<?php else: ?>
						<li class="last"><a href="<?php echo site_url('/admin'); ?>">Login</a></li>
					<?php endif; ?>
				</ul>

				<?php if ($this->session->userdata('session_user')): ?>	
					
					<h3 class="clear">Logged in as: <strong><?php echo $this->session->userdata('username'); ?></strong></h3>
				<?php endif; ?>	
			</div>

		</div>
		
		<div id="navigation">
			<ul id="menubar">
			<?php  if($this->session->userdata('session_user')): ?>
				<?php if (in_array('requests', $this->permission->permissions)): ?>
					<li>
					<a href="<?php echo site_url('/admin/summary'); ?>">Summary</a>		
						<ul class="subnav">
						
							<li><a href="<?php echo site_url('/admin/requests/viewall_open'); ?>">All Open Requests</a></li>		
							<li><a href="<?php echo site_url('/admin/requests/viewall_accepted'); ?>">All In-processing Requests</a></li>						
							<li><a href="<?php echo site_url('/admin/requests/viewall_archived'); ?>">All Quoted Requests </a></li>
					
						</ul>
					</li>

					<li><a href="<?php echo site_url('/admin/requests/view_my_summary'); ?>">My Summary</a>
						<ul class="subnav">
						<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
							<li><a href="<?php echo site_url('/admin/requests/view_my_open'); ?>">My Open Requests</a></li>	
							<li><a href="<?php echo site_url('/admin/requests/view_my_inprocess'); ?>">My In-processing Requests</a></li>	
							<li><a href="<?php echo site_url('/admin/requests/view_my_completed'); ?>">My Completed Requests</a></li>
						<?php endif; ?>	
						<?php if (in_array('requests_accept', $this->permission->permissions)): ?>
							<li><a href="<?php echo site_url('/admin/requests/view_my_accepted'); ?>">My Accepted Requests</a></li>	
							<li><a href="<?php echo site_url('/admin/requests/view_my_quoted'); ?>">My Processed Requests</a></li>
						<?php endif; ?>	
						
					
						
						<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
							<li><a href="<?php echo site_url('/admin/requests/add'); ?>">Add New Request</a></li>
						<?php endif; ?>	
						</ul>
					</li>
			
					
					<li><a href="<?php echo site_url('/admin/requests/archive'); ?>">History</a>
						
					</li>
				<?php endif; ?>	
			
				
				<?php if (in_array('shop', $this->permission->permissions)): ?>
					<li><a href="<?php echo site_url('/admin/shop/products'); ?>">Setting</a>
						<ul class="subnav">
							<?php if (in_array('shop_cats', $this->permission->permissions)): ?>
								<li><a href="<?php echo site_url('/admin/shop/categories'); ?>">Categories</a></li>
							<?php endif; ?>
							<li><a href="<?php echo site_url('/admin/shop/product_types'); ?>">Product Types</a></li>
							<?php if (in_array('shop_edit', $this->permission->permissions)): ?>
								<li><a href="<?php echo site_url('/admin/shop/product_fields'); ?>">Product fields</a></li>
							<?php endif; ?>
							
							
						</ul>
					</li>
				<?php endif ?>	
				
				<?php if (in_array('users', $this->permission->permissions)): ?>
					<li><a href="<?php echo site_url('/admin/users/viewall'); ?>">Users</a>
					<?php if (in_array('users_groups', $this->permission->permissions)): ?>
						<ul class="subnav">				
							<li><a href="<?php echo site_url('/admin/users/viewall'); ?>">All Users</a></li>
							<li><a href="<?php echo site_url('/admin/users/groups'); ?>">User Groups</a></li>
						</ul>
					<?php endif; ?>						
					</li>
				<?php endif; ?>
				<?php else: ?>
					<li><a href="<?php echo site_url('/admin'); ?>">Login</a></li>
				<?php endif; ?>					
			</ul>
			
		</div>
		
		<div id="content" class="content">
	