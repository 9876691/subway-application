<fieldset>
    <legend>Phone Numbers</legend>
    <?php $style_id = 'id="phone"'; ?>
    <?php for($i = 0; $i < count($phones); $i++) { ?>
    		
    <div <?php echo $style_id; ?> class="phone">
    	<input type="text" name="phone<?php echo $i ?>"
    		value="<?php echo $phones[$i]['ContactMethod']['detail']; ?>">
    			
    	<select name="location<?php echo $i ?>">
    		<?php foreach(array("Work", "Mobile", "Fax", 
    			"Pager", "Home", "Other") as $type): ?>
    		<?php $selected = ($type == $phones[$i]['ContactMethod']['name']) ? 'selected="selected"' : ''; ?>
    		<option <?php echo $selected; ?> value="<?php echo $type ?>"><?php echo $type ?></option>
			<?php endforeach; ?>
		</select>

		<a class="delete-field" href="#">
                  <?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?></a>
		<br>
	</div>
			
    <?php $style_id = ''; ?>
	<?php }; ?>
			
    <p id="phone_link"><a class="add-input" href="#">Add Another</a></p>
</fieldset>
    	
<fieldset>
    <legend>Email Addresses</legend>
    <?php $style_id = 'id="email"'; ?>
    <?php for($i = 0; $i < count($emails); $i++) { ?>
    	
    <div <?php echo $style_id; ?> class="email">
    	<input type="text" name="email<?php echo $i ?>"
    		value="<?php echo $emails[$i]['ContactMethod']['detail']; ?>">
    			
    	<select name="location<?php echo $i ?>">
    		<?php foreach(array("Work", "Home", "Other") as $type): ?>
    		<?php $selected = ($type == $emails[$i]['ContactMethod']['name']) ? 'selected="selected"' : ''; ?>
    		<option <?php echo $selected; ?> value="<?php echo $type ?>"><?php echo $type ?></option>
			<?php endforeach; ?>
		</select>
			
		<a class="delete-field" href="#">
                  <?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?>
                </a>
		<br>
	</div>
			
    <?php $style_id = ''; ?>
	<?php }; ?>
			
    <p id="email_link"><a class="add-input" href="#">Add Another</a></p>
</fieldset>
    	
<fieldset>
    <legend>Websites</legend>
    <?php $style_id = 'id="website"'; ?>
    <?php for($i = 0; $i < count($websites); $i++) { ?>
    	
    <div <?php echo $style_id; ?> class="website">
    	<input type="text" name="website<?php echo $i ?>"
    		value="<?php echo $websites[$i]['ContactMethod']['detail']; ?>">
    			
    	<select name="location<?php echo $i ?>">
    		<?php foreach(array("Work", "Personal", "Other") as $type): ?>
    		<?php $selected = ($type == $websites[$i]['ContactMethod']['name']) ? 'selected="selected"' : ''; ?>
    		<option <?php echo $selected; ?> value="<?php echo $type ?>"><?php echo $type ?></option>
			<?php endforeach; ?>
		</select>
			
		<a class="delete-field" href="#">
                  <?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?>
                </a>
		<br>
	</div>
			
    <?php $style_id = ''; ?>
	<?php }; ?>
			
    <p id="website_link"><a class="add-input" href="#">Add Another</a></p>
</fieldset>
    	
<fieldset>
    <legend>Addresses</legend>
    <?php $style_id = 'id="address"'; ?>
    <?php for($i = 0; $i < count($addresses); $i++) { ?>
    	
    <div <?php echo $style_id; ?> class="address">
    	<textarea name="street<?php echo $i ?>"><?php echo $addresses[$i]['Address']['street']; ?></textarea>
    			
    	<select name="location<?php echo $i ?>">
    		<?php foreach(array("Work", "Home", "Other") as $type): ?>
    		<?php $selected = ($type == $addresses[$i]['Address']['type']) ? 'selected="selected"' : ''; ?>
    		<option <?php echo $selected; ?> value="<?php echo $type ?>"><?php echo $type ?></option>
		<?php endforeach; ?>
		</select>
		<a class="delete-field" href="#"><img alt="trash" src="/img/trash.gif"></a><br>
    	
    	<input type="text" value="<?php echo $addresses[$i]['Address']['city']; ?>" name="city"><br>
    	
    	<input type="text" value="<?php echo $addresses[$i]['Address']['state']; ?>" name="state"> <br>
    	
    	<input type="text" value="<?php echo $addresses[$i]['Address']['zip']; ?>" name="zip"> <br>
    			
    	<?php echo $countryList->select('country', $addresses[$i]['Address']['country']); ?>
    	<br> 
	</div>
			
    <?php $style_id = ''; ?>
	<?php }; ?>
			
    <p id="address_link"><a class="add-input" href="#">Add Another</a></p>
</fieldset>