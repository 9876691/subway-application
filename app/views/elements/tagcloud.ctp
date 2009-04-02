<?php foreach($tags as $tag) { ?>
<?php if($type == 0) { ?>
<li><a href="<?php echo $html->url('/people/tag/') . $tag['tags']['id'] ?>"><?php echo $tag['tags']['name'] ?></a></li>
<?php } else if($type == 1) { ?>
<li><a href="<?php echo $html->url('/companies/tag/') . $tag['tags']['id'] ?>"><?php echo $tag['tags']['name'] ?></a></li>
<?php } else if($type == 2) { ?>
<li><a href="<?php echo $html->url('/kases/tag/') . $tag['tags']['id'] ?>"><?php echo $tag['tags']['name'] ?></a></li>
<?php } ?>
<?php } ?>