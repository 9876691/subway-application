<?php if($note['Note']['file_id']) { ?>
<p>Download <a href="<?php echo $html->url('/notes/download/') . $note['Note']['file_id'] ?>">
    <?php echo $note['Note']['file_name'] ?></a></p>
<?php }; ?>