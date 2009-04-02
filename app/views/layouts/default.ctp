<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?php echo $title_for_layout?></title>
<?php echo $html->css("reset-fonts-grids"); ?>
<?php echo $html->css("base-min"); ?>
<?php echo $html->css("application"); ?>
<?php echo $javascript->link("jquery-1.2.6.pack.js") ?>
<?php echo $javascript->link("jquery.livequery.pack.js") ?>
<?php echo $javascript->link("application.js") ?>
</head>

<body>
<div id="doc3" class="yui-t7">
	<div id="hd"><div>

            <div id="branding">
            	<a href="/dashboard"><?php echo $_SESSION['Account']['name'] ?></a>
            </div>

            <div id="user-menu">
                    <ul>
                            <li><strong>Welcome&nbsp;:&nbsp;</strong></li>
                            <li><a href="<?php echo $html->url('/people/view/') . $logged_on_user['id'] ?>">
                            <?php echo $logged_on_user['first_name'] . ' ' . $logged_on_user['surname'] ?></a>&nbsp;|&nbsp;</li>
                            <?php if($logged_on_user['administrator'] == 1) { ?>
                            <li><?php echo $html->link("Settings", "/admin/settings"); ?>&nbsp;|&nbsp;</li>
                            <li><?php echo $html->link("Users", "/admin/users"); ?>&nbsp;|&nbsp;</li>
                            <li><?php echo $html->link("Groups", "/admin/groups"); ?>&nbsp;|&nbsp;</li>
                            <?php } ?>
                            <li><?php echo $html->link("Log out", "/sessions/logout"); ?></li>
                    </ul>
            </div>

            <div id="nav" class="yui-b">

                    <ul class="clearfix">
                             <li<?php if($tab == 'dashboard') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("Dashboard", "/dashboard"); ?>
                             </p></li>
                             <li<?php if($tab == 'tasks') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("Tasks", "/tasks"); ?>
                             </p></li>
                             <li<?php if($tab == 'contacts') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("People", "/people"); ?>
                             </p></li>
                             <li<?php if($tab == 'companies') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("Companies", "/companies"); ?>
                             </p></li>
                             <li<?php if($tab == 'cases') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("Cases", "/kases"); ?>
                             </p></li>
                             <li<?php if($tab == 'search') { ?> class="selected"<?php } ?>><p>
                                    <?php echo $html->link("Search", "/search"); ?>
                             </p></li>
                             <?php foreach($lrutabs as $tab) { ?>
                             <li class="lru"><p>
                                    <a href="<?php echo $tab[0] ?>"><?php echo $tab[1] ?></a>
                             </p></li>
                             <?php } ?>
                    </ul>

            </div>

	</div></div>
	<div id="bd">

		<div id="yui-main">
		
                <?php echo $content_for_layout ?>

		</div>
	</div>
	<div id="ft">
	</div>
</div>
</body>
</html>