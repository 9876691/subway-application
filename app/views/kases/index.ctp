<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
	
	<div class="header">
	<h6>Cases</h6>
	</div>
	
	<div id="results">
	<?php if(!empty($tag)) { ?>
	<p>Cases tagged &quot;<?php echo $tag['Tag']['name'] ?>&quot;</p>
	<?php } ?>
	<table>
	<?php foreach ($kases as $kase): ?>
	<tr>
		<td><a href="<?php echo $html->url('/kases/view/') . $kase['Kase']['id'] ?>" class="image">
                    <?php echo $html->image('case.gif', array('alt' => 'Case',
                            'height' => '32', 'width' => '32')) ?>
		</a></td>
		<td>
			<a href="<?php echo $html->url('/kases/view/') . $kase['Kase']['id'] ?>">
			<?php echo $kase['Kase']['name']; ?></a><br />
			<?php if(!empty($kase['Kase']['updated_on'])) { ?>
			Updated <span class="localtime"><?php echo $kase['Kase']['updated_on']; ?></span>
			<?php } else { ?>
			This case is empty.
			<?php } ?>
		</td>
	</tr>
    <?php endforeach; ?>
    </table>

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
        <?php echo $html->image('buttons/add.png', array('alt' => 'Add Case')) ?>
          <a href="<?php echo $html->url('/kases/add/') ?>"><span>Add a new case</span></a>
        </p>
	
	<?php echo $this->renderElement('taglist'); ?>
</div>
</div>

