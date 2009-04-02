<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header clearfix">
        <?php if ($company['Company']['image_id'] != null) { ?>
	<div><img width="55" height="55" src="<?php echo $html->url('/notes/download/') . $company['Company']['image_id'] ?>" alt="Picture" /></div>
        <?php } else { ?>
	<div><?php echo $html->image('company.gif', array('alt' => 'Company')) ?></div>
        <?php } ?>
	<div class="person">
		<strong><?php echo $company['Company']['name']; ?></strong>
		<div id="tags">
		<?php echo $this->renderElement('tags'); ?>
		</div>
	</div>
	<div class="action">

          <?php if($editable) { ?>
          <a class="action"
		href="<?php echo $html->url('/companies/edit/') . $company['Company']['id'] ?>">
		Edit this company</a><br />
          <a class="action"
		href="<?php echo $html->url('/companies/delete/') . $company['Company']['id'] ?>">
		Delete this company</a><br />
          <a class="action"
                href="<?php echo $html->url('/companies/picture/') . $company['Company']['id'] ?>">Change Logo</a>
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
	
	<h2>Contact
        <?php if($editable) { ?><span><a href="<?php echo $html->url('/companies/edit/') . $company['Company']['id'] ?>">
	Edit</a></span><?php } ?>
        </h2>
           
	<?php echo $this->renderElement('contactinfo'); ?>
	
	<h2>About <?php echo $company['Company']['name']; ?>
        <?php if($editable) { ?><span><a href="<?php echo $html->url('/notes/about/') . $company['Company']['id'] ?>/1">Edit</a>
        </span><?php } ?>
        </h2>
	
	<p><?php echo $textile->text($about); ?></p>
	
	<h2>People in this company</h2>
	
	<ul class="people-sidebar">
	<?php if(! empty($people)) { ?>
        <table class="people-sidebar">
	<?php foreach ($people as $person): ?>
	<tr>
            <td>
            <?php if ($person['Person']['image_id'] != null) { ?>
            <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image"><img
              width="32" height="32"
              src="<?php echo $html->url('/notes/download/') . $person['Person']['image_id'] ?>"
              alt="Picture" /></a>
            <?php } else { ?>

            <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image">
              <?php echo $html->image('person.gif', array('alt' => 'Picture', 'height' => '32', 'width' => '32')) ?>
            </a>
            <?php } ?>
            </td><td>
		<a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>">
			<?php echo $person['Person']['first_name']; ?>&nbsp;
			<?php echo $person['Person']['surname']; ?>
		</a>
                <p><?php echo $person['Person']['title']; ?></p>
            </td>
	</tr>
    <?php endforeach; ?>
    </table>
    <?php } ?>
</ul>
</div>
</div>
