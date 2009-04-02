<p class="button" id="add-task-button">
    <?php echo $html->image('buttons/add.png', array('alt' => 'Add Task')) ?>
    <a href="#"><span>Add task</span></a>
</p>
<br>	
<div id="add-tasks" style="display: none;">
<?php if(! empty($associated_with_id)) {
	echo $this->renderElement('taskform', 
	array('association' => $associated_with_id, 
	'type' => $association_type, 'cancel' => 'add-tasks', 
	'show' => 'add-task-button')); }
	else {
	echo $this->renderElement('taskform', 
	array('cancel' => 'add-tasks', 'show' => 'add-task-button'));	
	}
?>
</div>
