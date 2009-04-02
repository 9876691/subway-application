<form id="note-form" enctype="multipart/form-data" 
	action="<?php echo $html->url('/notes/addnote') ?>" method="post">
    
    <fieldset>
    	<?php if(count($notes) > 0) { ?>
		<legend>Add a note to the history</legend>
		<?php } else { ?>
		<legend>Add the first note</legend>

		<p><strong>What is a note?</strong> Keep track of calls, conversations or
		meetings you have had.</p>
		<?php } ?>	
		
    	<input type="hidden" name="association_type" value="<?php echo $association_type ?>">
		<input type="hidden" name="associated_with_id" value="<?php echo $associated_with_id ?>">
		<textarea name="text" cols="40" rows="4"></textarea>
	</fieldset>
	
	<div id="note-options" style="display:none">
	<fieldset>
		<legend>Attach files</legend>

		<input type="file" name="File" id="file-attachment">
		
	</fieldset>
	
	<fieldset>
		<legend>Attach this note to a case</legend>
		
		<select name="kase_id">
		<option value="-1">No case</option>
		<?php foreach ($kases as $kase): ?>
		<option value="<?php echo $kase['Kase']['id'] ?>"><?php echo $kase['Kase']['name'] ?></option>
    	<?php endforeach; ?>
		</select>
		
	</fieldset>
	</div>

        <fieldset>
        <legend></legend>
	<input type="submit" value="Add this note">	
	<a id="note-options-toggle" href="#">Show options (cases and file attachments)</a>
	<a id="note-options-close" style="display:none" href="#">Hide options</a>
	<img id="spinner" style="display: none;" alt="Spinner" src="/img/spinner.gif">
        </fieldset>
</form>

<?php echo $this->renderElement('notelist'); ?>