<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	</div>

      <form method="post" class="extra-contact"
        action="<?php echo $html->url('/admin/editgroup/') . $this->data['Group']['id']; ?>">
		
    	<label>Name of Group</label>
    	
    	<?php echo $form->input('Group/name', array('size' => '40'))?> 		
        <br />
        
    	<label>Parent for this group</label>
    	
    	<?php echo $form->select('Group/parent_id', $groups, $selected)?>
		<br />
    	
        <input type="submit" value="Save" />
	</form>
	
</div></div>
<div class="yui-g sidebar">

</div>
</div>