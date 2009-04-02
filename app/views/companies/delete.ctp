<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Delete <?php echo $company['Company']['name']; ?> ?</h6>
	</div>

        <div id="delete">
	<p>All notes and files directly associated with <?php echo $company['Company']['name']; ?> will
	be deleted. Any people attached to New Company will not be deleted
	they will just no longer be attached to this company.</p>

	<p>Note: There is no undo. Deletion is permanent.</p>
	
	<form action="<?php echo $html->url('/companies/delete/') . $id ?>" method="post">
	<input type="submit" value="I understand - delete <?php echo $company['Company']['name']; ?>" />
	&nbsp;or&nbsp;<?php echo $html->link("Cancel", "/companies/view/" . $id); ?>
	</form>
        </div>
</div></div>
</div>