<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Find someone by typing their name</h6>
	<form id="main-search" action="<?php echo $html->url('/people/partial') ?>" method="post">
	<input autocomplete="off" name="search_field" id="search_field" type="text" />
        <?php echo $html->image('spinner.gif', array('id' => 'spinner', 'style' => 'display:none')) ?>
    </form>
	</div>
	
	<div id="results">
	<?php if(!empty($tag)) { ?>
	<p>Contacts tagged &quot;<?php echo $tag['Tag']['name'] ?>&quot;</p>
	<?php } ?>
	
	<?php echo $this->renderElement('people'); ?>

        <?php if(isset($pagination)) { ?>
        <div class="pagination">
            <?php echo $paginator->prev('Previous ', null, null, array('class' => 'disabled')); ?>
            <span class="pipe">&nbsp;|&nbsp;</span>
            <?php echo $paginator->next(' Next', null, null, array('class' => 'disabled')); ?>
            (Page <?php echo $paginator->counter(); ?>)
        </div>
	<?php } ?>
    </div>
</div></div>
<div class="yui-g sidebar">
	<p class="button">
        <?php echo $html->image('buttons/add.png', array('alt' => 'Add Person')) ?>
          <a href="<?php echo $html->url('/people/add') ?>"><span>Add a new person</span></a>
        </p>
	
	<?php echo $this->renderElement('taglist'); ?>
	
	<h2>Import</h2>

	<p class="button">
        <?php echo $html->image('buttons/building_go.png', array('alt' => 'Import vCard(s)')) ?>
          <a href="<?php echo $html->url('/admin/import') ?>"><span>Import vCard(s)</span></a>
        </p>

	<p class="button">
        <?php echo $html->image('buttons/transmit_go.png', array('alt' => 'Export vCard(s)')) ?>
          <a href="<?php echo $html->url('/admin/import') ?>"><span>Export vCard(s)</span></a>
        </p>
</div>
</div>

