
	<?php if(empty($people)) { ?>
	<p>There are no users associated with this group</p>
	<?php } else { ?>
	<ul>
	<?php foreach($people as $person) { ?>
	<li><?php echo $person['Person']['first_name'] . ' ' .
		$person['Person']['surname'] ?>	
	<?php } ?>
	</ul>
	<?php } ?>