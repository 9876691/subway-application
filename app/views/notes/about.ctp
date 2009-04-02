<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
		
	<div class="header">
	<p>Edit info about <a href="<?php echo $link ?>"><?php echo $name ?></a></p>
	</div>
		 
	<form  class="edit-note" method="post" action="<?php echo $post_form ?>">
   
   		<fieldset>
    	<textarea name="text"><?php echo $note ?></textarea>		
   		</fieldset>
    	
    	<fieldset>
    		<?php echo $form->submit('Save changes') ?>
    		&nbsp;or&nbsp;
    		<a href="<?php echo $link ?>">Cancel</a>
    	</fieldset>
    </form>
	
</div></div>
<div class="yui-g sidebar">

</div>
</div>
