<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Delete <?php echo $kase['Kase']['name']; ?> ?</h6>
	</div>

        <div id="delete">
	<p>All notes and files directly associated with <?php echo $kase['Kase']['name']; ?> will
	be deleted. Any people attached to <?php echo $kase['Kase']['name']; ?> will not be deleted
	they will just no longer be attached to this company.</p>

	<p>Note: There is no undo. Deletion is permanent.</p>
	
	<form action="<?php echo $html->url('/kases/delete/') . $id ?>" method="post">
	<input type="submit" value="I understand - delete this Case" />
	&nbsp;or&nbsp;<?php echo $html->link("Cancel", "/kases/view/" .$id); ?>
	</form>
        </div>
</div></div>
</div>