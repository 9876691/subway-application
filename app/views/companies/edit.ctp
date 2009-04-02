<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	 
	<form method="post" class="extra-contact" 
		action="<?php echo $html->url('/companies/edit/' .$this->data['Company']['id']) ?>">
   
		<div class="header clearfix">
    	<?php echo $form->input('Company/name', array('size' => '40'))?> 		
    	</div>
    	
		<?php echo $this->renderElement('contactfields'); ?>
    	
    	<div class="hr"></div>
    	
    	<fieldset class="submit">
    		<?php echo $form->submit('Save this company') ?>
    		&nbsp;or&nbsp;
    		<?php echo $html->link("cancel", "/companies/"); ?>
    	</fieldset>
    </form>
</div></div>
<div class="yui-g sidebar">
	<h2>Other ways to add companies</h2>
	<p><?php echo $html->link("Upload a vCard file", "/people/add"); ?></p>
</div>
</div>
