<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?php echo $title_for_layout?></title>
<?php echo $html->css("reset-fonts-grids"); ?>
<?php echo $html->css("base-min"); ?>
<?php echo $html->css("external"); ?>
<?php echo $javascript->link("jquery-1.2.6.pack.js") ?>
<?php echo $javascript->link("jquery.livequery.pack.js") ?>
<?php echo $javascript->link("application.js") ?>
</head>

<body>
<div id="doc3" class="yui-t7">
	<div id="hd"></div>
	<div id="bd">

		<div id="yui-main">
		<?php $session->flash(); ?> 
                <?php echo $content_for_layout ?>

		</div>

	</div>
	<div id="ft">
	</div>
</div>
</body>
</html>