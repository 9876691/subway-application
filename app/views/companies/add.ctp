<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Add a new company</h6>
	</div>
	
    <form method="post" class="extra-contact" action="<?php echo $html->url('/companies/add')?>">
    	<fieldset>
    		<?php echo $form->input('Company/name', array('size' => '40'))?> 	
    	</fieldset>
    	
    	<div class="hr"></div>
    	
    	<div id="extra_contact_link">
    		<p><a href="#">Add contact information</a> You can do this later.</p>
    	</div>
    	<br />
    	<div id="extra_contact" style="display:none">
		<?php echo $this->renderElement('contactfields'); ?>
    	</div>
    	
    	<div class="hr"></div>
    	
    	<fieldset>
    		<?php echo $form->submit('Add this company') ?>
    		&nbsp;or&nbsp;
    		<?php echo $html->link("cancel", "/companies/"); ?>
    	</fieldset>
    </form>
</div></div>
<div class="yui-g sidebar">
</div>
</div>
