<h1 class="headingleft">Feedback</h1>

<div class="headingright">
	<?php if (in_array('feedback_process', $this->permission->permissions)): ?>
		
	<?php endif; ?>
</div>

<?php if ($feedback): ?>

<?php echo $this->pagination->create_links(); ?>

<table class="default clear">
	<tr>
		<th><?php echo order_link('/admin/feedback/viewall','subject','Feedback'); ?></th>
		<th><?php echo order_link('/admin/feedback/viewall','name','Name'); ?></th>
		<th><?php echo order_link('/admin/feedback/viewall','email','Email'); ?></th>
		<th><?php echo order_link('/admin/feedback/viewall','datecreated','Date'); ?></th>
		<th class="narrow"><?php echo order_link('/admin/feedback/viewall','archived','Archived'); ?></th>
		
		<th class="tiny">&nbsp;</th>
	</tr>
<?php foreach ($feedback as $post): ?>
	<tr class="<?php echo (!$post['archived']) ? 'draft' : ''; ?>">
		<td><?php echo (in_array('feedback_process', $this->permission->permissions)) ? anchor('/admin/feedback/process_feedback/'.$post['feedbackID'], $post['subject']) : $post['subject']; ?></td>
		<td >
			<?php echo $post['name']; ?>
		</td>
		<td >
			<?php echo $post['email']; ?>
		</td>
		<td><?php echo dateFmt($post['dateCreated'], '', '', TRUE); ?></td>
		<td>
			<?php
				if ($post['archived']) echo '<span style="color:green;">Yes</span>';
				else echo 'No';
			?>
		</td>
		
		<td class="tiny">			
			<?php if (in_array('feedback_process', $this->permission->permissions)): ?>
				<?php echo anchor('/admin/feedback/delete_post/'.$post['feedbackID'], 'Delete', 'onclick="return confirm(\'Are you sure you want to delete this?\')"'); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->pagination->create_links(); ?>

<p style="text-align: right;"><a href="#" class="button grey" id="totop">Back to top</a></p>

<?php else: ?>

<p class="clear">There are no feedback yet.</p>

<?php endif; ?>