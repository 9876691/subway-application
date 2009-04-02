<div class="yui-ge">
<div class="yui-g first"><div class="shadow">
		
	<div class="header">
	<p>Note about <a href="<?php echo $link ?>"><?php echo $name ?></a></p>
	<p>Added by <?php echo $note['Person']['first_name'] . ' ' . $note['Person']['surname'] ?></p>
	<p class="action"><a class="action" 
		href="<?php echo $html->url('/notes/edit/') . $note['Note']['id'] ?>">
		Edit this note</a>
	<a class="action delete-note" 
		href="<?php echo $html->url('/notes/delete/') . $note['Note']['id'] ?>">
		Delete this note</a>
	</p>
	</div>

        <div class="note-view">
	<p><?php echo $textile->text($note['Note']['text']); ?></p>
        <?php echo $this->renderElement('download', array('note' => $note)); ?>
	</div>
</div></div>
<div class="yui-g sidebar">

</div>
</div>
