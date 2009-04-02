<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
		
	<div class="header">
	<p>Edit this note about <a href="<?php echo $link ?>"><?php echo $name ?></a></p>
	</div>
		 
	<form class="edit-note" method="post"
		action="<?php echo $html->url('/notes/edit/') . $this->data['Note']['id']; ?>">
   
   		<fieldset>
                <?php echo $form->textarea('Note/text')?>
   		</fieldset>
    	
    	<fieldset>
    		<?php echo $form->submit('Save this note') ?>
    		&nbsp;or&nbsp;
    		<a href="<?php echo $html->url('/notes/view/') . $note['Note']['id'] ?>">Cancel</a>
    	</fieldset>
    </form>
	
</div></div>
<div class="yui-g sidebar">

</div>
</div>
