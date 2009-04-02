<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
		
	<div class="header">
	<p>Settings</p>
	</div>
		 
	<form method="post" class="normal"
		action="<?php echo $html->url('/admin/settings/') ?>">
   
   		<fieldset>
                        <?php echo $form->input('Account/name')?>
   		</fieldset>
   		
   		<fieldset>
   			<legend>How do you wish to share (Contacts, Companies, Notes etc) between users.</legend>
   			<?php $sel = $this->data['Account']['security_level']; ?>
   			
   			<dl>
   				<dt><input type="radio" <?php if($sel == 'low') echo 'checked="checked"' ?>
   					name="data[Account][security_level]" value="low" /></dt>
   				<dd>All users have read write access to all records 
   				regardless of what group they belong to.
   				</dd>
   				<dt><input type="radio" <?php if($sel == 'medium') echo 'checked="checked"' ?>
   					name="data[Account][security_level]" value="medium" /></dt>
   				<dd>Read only access to records belonging to users in higher 
   				level groups, and read/write access to records 
   				in there group and groups below.
   				</dd>
   				<dt><input type="radio" <?php if($sel == 'high') echo 'checked="checked"' ?>
   					name="data[Account][security_level]" value="high" /></dt>
   				<dd>No access to records outside of the users group. 
   				Read write access to records in the same group and 
   				groups below.
   				</dd>
   			</dl>
   			
    	</fieldset>
    	
    	<fieldset>
    		<?php echo $form->submit('Save these settings') ?>
    	</fieldset>
    </form>
	
</div></div>
<div class="yui-g sidebar">

</div>
</div>
