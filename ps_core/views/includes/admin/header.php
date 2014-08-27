<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="icon" href="<?php echo $this->config->item('staticPath'); ?>/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/admin.css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/lightbox.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('staticPath'); ?>/css/datepicker.css" media="screen" />
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.ui.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.lightbox.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/default.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/admin.js"></script>

	<script language="JavaScript">			
		$(function(){
			$('ul#menubar li').hover(
				function() { $('ul', this).css('display', 'block').parent().addClass('hover'); },
				function() { $('ul', this).css('display', 'none').parent().removeClass('hover'); }
			);			
		});		
	</script>		
	
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
					<li><a href="<?php echo site_url('/admin/summary'); ?>">Summary</a>						
					</li>

					<li><a href="<?php echo site_url('/admin/requests/viewall'); ?>">Requests</a>
						<ul class="subnav">
							<li><a href="<?php echo site_url('/admin/requests/viewall'); ?>">All Requests</a></li>
						<?php if (in_array('requests_edit', $this->permission->permissions)): ?>
							<li><a href="<?php echo site_url('/admin/requests/add_request'); ?>">Add Request</a></li>
						<?php endif; ?>	
						</ul>
					</li>
			
					<li><a href="<?php echo site_url('/admin/quotes/viewall'); ?>">Accepted Quotes</a>
						
					</li>
				
					<li><a href="<?php echo site_url('/admin/requests/history'); ?>">History</a>
						
					</li>
				<?php endif; ?>	
				<?php if (in_array('pages_templates', $this->permission->permissions)): ?>
					<li><a href="<?php echo site_url('/admin/pages/templates'); ?>">Templates</a>
						<ul class="subnav">
							<li><a href="<?php echo site_url('/admin/pages/templates'); ?>">All Templates</a></li>
							<li><a href="<?php echo site_url('/admin/pages/includes'); ?>">Includes</a></li>
							<li><a href="<?php echo site_url('/admin/pages/includes/css'); ?>">CSS</a></li>
							<li><a href="<?php echo site_url('/admin/pages/includes/js'); ?>">Javascript</a></li>
							<li><a href="<?php echo site_url('/admin/pages/includes/slide'); ?>">Slide</a></li>
						</ul>
					</li>
				<?php endif; ?>	
				<?php if (in_array('images', $this->permission->permissions)): ?>
					<li><a href="<?php echo site_url('/admin/images/viewall'); ?>">Uploads</a>
						<ul class="subnav">				
							<li><a href="<?php echo site_url('/admin/images/viewall'); ?>">Images</a></li>
							<?php if (in_array('images_all', $this->permission->permissions)): ?>
								<li><a href="<?php echo site_url('/admin/images/folders'); ?>">Image Folders</a></li>
							<?php endif; ?>
							<?php if (in_array('files', $this->permission->permissions)): ?>
								<li><a href="<?php echo site_url('/admin/files/viewall'); ?>">Files</a></li>
								<?php if (in_array('files_all', $this->permission->permissions)): ?>								
									<li><a href="<?php echo site_url('/admin/files/folders'); ?>">File Folders</a></li>						
								<?php endif; ?>
							<?php endif; ?>								
						</ul>
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
	