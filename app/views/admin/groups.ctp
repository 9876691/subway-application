<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h2>Groups</h2>
	</div>
	
	<div id="groups">
	<?php echo $this->renderElement('grouplist'); ?>
	</div>

</div></div>
<div class="yui-g sidebar">

<form id="group-form" action="<?php echo $html->url('/admin/addgroup/') ?>" method="post">
    <fieldset>
    	<legend>Make a new group</legend>
    	<label>Name of Group</label>
    	<input type="text" id="name" name="name" /><br />
    	
    	<label>Parent for this group</label>
    	<?php echo $form->select('parent_id', $groups)?>
		<br />
    	
		<input type="submit" value="Create group">	
		<img id="spinner" style="display: none;" src="/img/spinner.gif" />	
	</fieldset>
</form>	

</div>
</div>