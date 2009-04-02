<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Add a new person</h6>
	</div>
	
    <form method="post" id="people-add" class="extra-contact" action="<?php echo $html->url('/people/add')?>">
    	<?php echo $form->input('Person/first_name', array('size' => '40'))?> 		
    	<?php echo $form->input('Person/surname', array('size' => '40'))?>
    	<?php echo $form->input('Person/title', array('size' => '25', 'label' => array('text' => 'Title (e.g. Director)')))?>
    	<?php echo $form->input('Person/company', 
          array('size' => '40', 'id' => 'company-search',
          'autocomplete' => 'off' ))?>
    	<div id="company-search-result" class="autocompleter"></div>
    	
    	<div class="hr"></div>
    	
    	<div id="extra_contact_link">
    		<p><a href="#">Add contact information</a> You can do this later.</p>
    	</div>
    	
    	<div id="extra_contact" style="display:none">
		<?php echo $this->renderElement('contactfields'); ?>
    	</div>
    	
    	<div class="hr"></div>
    	
    	<fieldset>
    		<?php echo $form->submit('Add this person') ?>
    		&nbsp;or&nbsp;
    		<?php echo $html->link("cancel", "/people/"); ?>
    	</fieldset>
    </form>
</div></div>
<div class="yui-g sidebar">
	<h2>Other ways to add people</h2>

	<p class="button">
          <?php echo $html->image('buttons//building_go.png', array('alt' => 'Import')) ?>
          <a href="<?php echo $html->url('/admin/import') ?>"><span>Import vCard(s)</span></a>
        </p>
</div>
</div>
