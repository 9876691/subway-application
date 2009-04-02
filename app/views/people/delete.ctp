<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Delete <?php echo $person['Person']['first_name'].' '.$person['Person']['surname']; ?> ?</h6>
	</div>

        <div id="delete">
	<p>All notes and files directly associated with <?php echo $person['Person']['first_name'].' '.$person['Person']['surname']; ?> will
	be deleted.</p>

	<p>Note: There is no undo. Deletion is permanent.</p>
	
	<form action="<?php echo $html->url('/people/delete/') . $id ?>" method="post">
	<input type="submit" value="I understand - delete <?php echo $person['Person']['first_name'].' '.$person['Person']['surname']; ?>" />
	&nbsp;or&nbsp;<a href="<?php echo $html->url('/people/view/') . $id ?>">Cancel</a>
	</form>
        </div>
</div></div>
</div>