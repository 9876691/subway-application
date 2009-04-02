<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header clearfix">
	<div><?php echo $html->image('case.gif', array('alt' => 'Case')) ?></div>
	<div class="person">
		<strong><?php echo $kase['Kase']['name']; ?></strong>
		<div id="tags">
		<?php echo $this->renderElement('tags'); ?>
		</div>
	</div>
	<div class="action">
          <?php if($editable) { ?>
          <a class="action" href="<?php echo $html->url('/kases/edit/') . $kase['Kase']['id'] ?>">
          Edit this case</a><br>
          <a class="action"
          href="<?php echo $html->url('/kases/delete/') . $kase['Kase']['id'] ?>">
          Delete this case</a>
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
	
	<h2>About <?php echo $kase['Kase']['name']; ?>
        <?php if($editable) { ?><span><a href="<?php echo $html->url('/notes/about/') . $kase['Kase']['id']; ?>/2">Edit</a>
        </span><?php } ?></h2>
	
	<p><?php echo $about ?></p>
		
	<h2>People in this case</h2>
	
	<ul class="sidebar-list">
	<?php foreach ($people as $person): ?>
	<li class="clearfix">
                <p class="kase-pic"><?php if ($person['Person']['image_id'] != null) { ?>

                <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image"><img
                  width="32" height="32"
                  src="<?php echo $html->url('/notes/download/') . $person['Person']['image_id'] ?>"
                  alt="Picture"></a>
                <?php } else { ?>
                <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image">
                <?php echo $html->image('person.gif', array('alt' => 'Picture', 'height' => '32', 'width' => '32')) ?>
                </a>
                <?php } ?></p>

		<p class="name">
			<a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>">
			<?php echo $person['Person']['first_name']; ?>&nbsp;
			<?php echo $person['Person']['surname']; ?></a><br>
			<a href="<?php echo $html->url('/companies/view/') . $person['Company']['id'] ?>">
			<?php echo $person['Company']['name'] ?>
			</a>
		</p>
	</li>
    <?php endforeach; ?>
	</ul>
	
	<h2>Companies in this case</h2>
	
	<ul class="sidebar-list">
	<?php foreach ($companies as $company): ?>
	<li class="clearfix">
                <p class="kase-pic"><?php if ($company['Company']['image_id'] != null) { ?>
                  <a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>" class="image">
                  <img width="32" height="32"
                  src="<?php echo $html->url('/notes/download/') . $company['Company']['image_id'] ?>"
                  alt="Picture"></a>
                <?php } else { ?>
                  <a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>" class="image">
                    <?php echo $html->image('company.gif', array('alt' => 'Company', 'height' => '32', 'width' => '32')) ?>
                  </a>
                <?php } ?></p>

		<p class="name">
			<a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>">
			<?php echo $company['Company']['name']; ?></a>
		</p>
	</li>
    <?php endforeach; ?>
	</ul>
</div>
</div>
