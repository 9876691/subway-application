<li>
<span class="nubbin">
	<a class="delete" href="<?php echo $html->url('/tasks/delete/') . $task['Task']['id'] ?>">
		<?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?>
	</a>
	<a class="edit" href="<?php echo $html->url('/tasks/edit/') . $task['Task']['id'] ?>">
	<?php echo $html->image('task-icons/document.gif', array('alt' => 'Edit')) ?>
	</a>
</span>
<input class="complete" name="done" type="checkbox" value="<?php echo $task['Task']['id'] ?>" />
<?php if(! empty($task['Task']['due_date']) && ! empty($show_time)) { ?>
<strong><?php echo $task['Task']['due_date'] ?></strong>
<?php } ?>
<?php if(! empty($task['Task']['due_time'])) { ?>
	<?php if(empty($show_time)) { ?><strong><?php } ?>
	<?php echo $task['Task']['due_time'] ?>	
	<?php if(empty($show_time)) { ?></strong><?php } ?>
	<?php } ?>
<?php echo $task['Task']['subject'] ?>
<?php if(! empty($task['Task']['associated_url'])) { ?>
<span class="association">
&nbsp;(Re&nbsp;:&nbsp;<a href="<?php echo $task['Task']['associated_url'] ?>"><?php echo $task['Task']['associated_name'] ?></a>)
</span>
<?php } ?>
</li>