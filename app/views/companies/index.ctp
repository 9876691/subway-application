<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Find a company by typing its name</h6>
	<form id="main-search" action="/companies/partial" method="post">
	<input autocomplete="off" name="search_field" id="search_field" type="text" />
        <?php echo $html->image('spinner.gif', array('id' => 'spinner', 'style' => 'display:none')) ?>
    </form>
	</div>
	
	<div id="results">
	<?php if(!empty($tag)) { ?>
	<p>Companies tagged &quot;<?php echo $tag['Tag']['name'] ?>&quot;</p>
	<?php } ?>
	<?php echo $this->renderElement('companies'); ?>

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
        <?php echo $html->image('buttons/add.png', array('alt' => 'Add Company')) ?>
        <a href="<?php echo $html->url('/companies/add/') ?>"><span>Add a new company</span></a></p>
	
	<?php echo $this->renderElement('taglist'); ?>
</div>
</div>

