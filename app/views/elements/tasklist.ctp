<?php if(! empty($overdue)) { ?>
<h3 class="overdue">Overdue</h3>
<ul>
	<?php foreach ($overdue as $task): ?>
	<?php echo $this->renderElement('task', array('task' => $task,
		'show_time' => 'true')); ?>	
    <?php endforeach; ?>
</ul>
<?php } ?>
<?php if(! empty($today)) { ?>
<div class="today">
<h3 class="today">Today</h3>
<ul class="today">
	<?php foreach ($today as $task): ?>
	<?php echo $this->renderElement('task', array('task' => $task)); ?>	
    <?php endforeach; ?>
</ul>
</div>
<?php } ?>
<?php if(! empty($later)) { ?>
<h3>Later</h3>
<ul>
	<?php foreach ($later as $task): ?>
	<?php echo $this->renderElement('task', array('task' => $task,
		'show_time' => 'true')); ?>	
    <?php endforeach; ?>
</ul>
<?php } ?>
