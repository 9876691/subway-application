<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header clearfix">
        <?php if ($person['Person']['image_id'] != null) { ?>
	<div><img width="55" height="55" src="<?php echo $html->url('/notes/download/') . $person['Person']['image_id'] ?>" alt="Picture" /></div>
        <?php } else { ?>
	<div><?php echo $html->image('person.gif', array('alt' => 'Person')) ?></div>
        <?php } ?>
	<div class="person">
		<strong><?php echo $person['Person']['first_name'].' '.$person['Person']['surname']; ?></strong>
		<br />
		<?php echo $person['Person']['title'] ?> at <a href="<?php echo $html->url('/companies/view/') . $person['Company']['id'] ?>"><?php echo $person['Company']['name'] ?></a>
		<div id="tags">
		<?php echo $this->renderElement('tags'); ?>
		</div>
	</div>
	<div class="action">
                <?php if($editable) { ?>
		<a class="action" href="<?php echo $html->url('/people/edit/') . $person['Person']['id'] ?>">
		Edit this person</a><br />
		<a class="action" 
			href="<?php echo $html->url('/people/delete/') . $person['Person']['id'] ?>">
			Delete this person</a><br />
		<a class="action"
			href="<?php echo $html->url('/people/picture/') . $person['Person']['id'] ?>">
			Change picture</a>
                <?php if(! empty($person['Person']['invitable'])) { ?>
                <br />
		<a class="action"
			href="<?php echo $html->url('/people/invite/') . $person['Person']['id'] ?>">
			Invite to become a user</a>
                <?php } ?>
                <?php } ?>
	</div>
	</div>
	
	<div id="notes">
		<?php echo $this->renderElement('noteform'); ?>
	</div>
	
</div></div>
<div class="yui-g sidebar">
	
	<?php echo $this->renderElement('taskbutton'); ?>
	
	<div id="tasks">
	<?php echo $this->renderElement('tasklist'); ?>
	</div>
	
	<h2>Contact <?php if($editable) { ?>
            <span><a href="<?php echo $html->url('/people/edit/') . $person['Person']['id'] ?>">Edit</a>
            </span><?php } ?>
        </h2>
            
	<?php echo $this->renderElement('contactinfo'); ?>
	
	<h2>About <?php echo $person['Person']['first_name'].' '.$person['Person']['surname']; ?>
        <?php if($editable) { ?><span>
        <a href="<?php echo $html->url('/notes/about/') . $person['Person']['id'] ?>/0">Edit</a>
        </span><?php } ?></h2>
	
	<p><?php echo $textile->text($about); ?></p>
</div>
</div>
