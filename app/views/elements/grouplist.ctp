<?php function list_groups($html, $groups) { ?>
<ul>
<?php foreach ($groups as $group): ?>
	<li><p><?php echo $group['Group']['name'] ?>&nbsp;
		<a href="<?php echo $html->url('/admin/group/') . $group['Group']['id'] ?>">View</a>&nbsp;
		<a href="<?php echo $html->url('/admin/editgroup/') . $group['Group']['id'] ?>">Edit</a>&nbsp;
		<a href="<?php echo $html->url('/admin/deletegroup/') . $group['Group']['id'] ?>">
                 <?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?>
                </a></p>
	<?php if(!empty($group['Group']['children'])) { ?>
	<?php list_groups($html, $group['Group']['children']); } ?>
	</li>
<?php endforeach; ?>
</ul>
<?php } ?>
<?php list_groups($html, $hierarchy); ?>
