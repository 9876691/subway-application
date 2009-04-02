<ul>
	<?php foreach ($people as $person): ?>
	<li>
		<a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>">
			<?php echo $person['Person']['first_name']; ?>&nbsp;
			<?php echo $person['Person']['surname']; ?></a>
	</li>
    <?php endforeach; ?>
</ul>
