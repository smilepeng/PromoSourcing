<?php if ($recentOpenRequests): ?>
			<h3>Recent Open Requests</h3>
			
			<ul>	
			<?php foreach($recentOpenRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName'];  ?> <input type="image" src="<?php echo $this->config->item('staticPath'). "/images/".trim($request['imageName']) ;  ?>" /> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ($recentOpenRequests): ?>
			<h3>Recent Accepted Requests</h3>
			
			<ul>	
			<?php foreach($recentAcceptedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName'];  ?> <input type="image" src="<?php echo $this->config->item('staticPath'). "/images/".trim($request['imageName']) ;  ?>" /><?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ($recentCompletedRequests): ?>
			<h3>Recent Completed Requests</h3>
			
			<ul>	
			<?php foreach($recentCompletedRequests as $request): $style = ''; ?>			
				<li style="background: #FFFCDF;">
					<?php echo dateFmt($request['dateCreated'], 'g:i a'). $request['productName'];  ?><input type="image" src="<?php echo $this->config->item('staticPath'). "/images/".trim($request['imageName']) ;  ?>" /> <?php echo (strtotime($request['dateCreated']) >= strtotime('-2 minutes')) ? '<em>just now</em>' : ''; ?>	
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>