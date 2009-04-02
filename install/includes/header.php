<?

// Declare Variables
$pagename = NULL;
$page = NULL;

$page = $_GET['page'];
if (!isset($page)) { $pagename = "Start"; $img1 = "start.gif"; }
else if ($page == 1) { $pagename = "Info"; $img2 = "info.gif"; }
else if ($page == 2) { $pagename = "Update Info"; $img2 = "info.gif"; }
else if ($page == 3) { $pagename = "Run SQL"; $img3 = "runsql.gif"; }
else if ($page == 4) { $pagename = "Finish"; $img4 = "finish.gif"; }
else { die(); }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
        <title>SubwayCRM Install  - <?php echo $pagename; ?></title>
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.3.0/build/reset-fonts-grids/reset-fonts-grids.css">
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.3.0/build/base/base-min.css">
        <link rel='stylesheet' type='text/css' href="style.css">
     </head>
    <body>
      <div id="custom-doc">
        <div id="hd"></div>
        <div id="bd">


