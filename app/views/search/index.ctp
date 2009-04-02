<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h2>Search notes, emails and comments</h2>
	<form id="search-page" method="get">
		<input type="text" name="search" value="<?php echo $search ?>" />
                <input type="submit" value="Search" />
	</form>
	</div>

	<?php echo $this->renderElement('notelist'); ?>
</div></div>
<div class="yui-g sidebar">
</div>
</div>
