<div id="tags-edit-button" style="clear:both">
<ul class="tag-list clearfix">
<?php if(!empty($tags)) { ?>
<?php echo $this->renderElement('tagcloud'); ?>
<?php } ?>
<?php if(empty($tags)) { ?>
<li><a class="tag-form-url" href="#">Add tags</a></li>
<?php } else { ?>
<li><a class="tag-form-url" href="#">Edit tags</a></li>
<?php } ?>
</ul>
</div>
<div id="tag-edit" style="display:none;clear:both">
<?php if(!empty($tags)) { ?>
<ul class="tag-list">
	<?php foreach($tags as $tag) { ?>
	<li><?php echo $tag['tags']['name'] ?>
	<a class="tag-delete" href="<?php echo $html->url('/tags/delete/') . $tag['ats']['id'] ?>">
          <?php echo $html->image('trash.gif', array('alt' => 'Delete')) ?></a></li>
	<?php } ?>
</ul>
<?php } ?>
<form id="tag-form" action="<?php echo $html->url('/tags/addtag/') ?>" style="display:hidden">
<fieldset>
    <legend></legend>
    <input type="text" size="5" id="tag-name" name="tag">
    <input type="submit" value="Add tag">
    <a id="tag-edit-cancel" href="#">Cancel</a>
    <input type="hidden" id="tag-assoc" name="associated_with_id" value="<?php echo $association ?>">
    <input type="hidden" id="tag-type" name="association_type" value="<?php echo $type ?>">
</fieldset>
</form>
</div>