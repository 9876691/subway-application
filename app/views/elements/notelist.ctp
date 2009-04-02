<?php if (count($notes) > 0) { ?>
<ul id="notes-list">
	<?php foreach ($notes as $note): ?>
	<li>
	<p><strong><span class="localtime"><?php echo $note['Note']['modified'] ?></span></strong>
	&nbsp<a href="<?php echo $html->url('/notes/view/') . $note['Note']['id'] ?>">Note by <?php echo $note['Person']['first_name'] . ' ' . $note['Person']['surname'] ?></a></p>
	<?php echo $textile->text($note['Note']['text']); ?>
        <?php echo $this->renderElement('download', array('note' => $note)); ?>
	</li>
   	<?php endforeach; ?>
</ul>
<?php } ?>
