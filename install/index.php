<?php

include("includes/header.php");

$page = $_GET['page'];

if (!isset($page)) {
echo '
<div class="yui-g">
  <div class="yui-u first">
    <h3><a href="index.php?page=1">Install SubwayCRM</a></h3>
  </div>

  <div class="yui-u">
    <h3><a href="index.php?page=2">Upgrade SubwayCRM</a></h3>
  </div>
</div>

<div><p>If this is your first time installing then please select the 
<strong>Install SubwayCRM</strong> option
above as this will install all tables and SQL data needed to run the script.
</p></div>

<div><p>If you have previously installed Status2k and wish to update please
select the <strong>Upgrade SubwayCRM</strong> option, please note that if
 you are updating from version 1.0 or
earlier then you must perform a fresh install.</p>
';
} else if ($page == "1") {
echo '
<form method="POST" action="index.php?page=3">
<div class="yui-g">
  <div class="yui-u first">
      <input type="hidden" name="sql" value="install.sql">
      <label>First Name</label><br>
      <input type="text" name="firstname" size="20"><br>
      <label>Last Name</label><br>
      <input type="text" name="lastname" size="20"><br>
      <label>Username</label><br>
      <input type="text" name="user" size="20"><br>
      <label>Password:</label><br>
      <input type="password" name="pass" size="20"><br>
      <label>Email</label><br>
      <input type="text" name="emil" size="25">
      <p>Please enter the required information before continuing.</p>
      <br>
  </div>

  <div class="yui-u">
    <br><br><br>
      <input type="submit" value="Continue &gt;&gt;">
  </div>
</div>
</form>

';
} else if ($page == "2") {
echo '
<p>Please select the correct update from below.</p>
<form method="POST" action="index.php?page=3">
<select size="1" name="sql">
<option value="update25.sql" selected>Update v2.4 To v2.5</option>
<option value="update24.sql" selected>Update v2.3 To v2.4</option>
</select><br>
<input type="submit" value="Update &gt;&gt;">
</form>
';
} else if ($page == "3") {
$install = 1;
require_once("../app/config/database.php");

$db_config = new DATABASE_CONFIG();
$db_name = $db_config->default['database'];
$prefix = $db_config->default['prefix'];

echo '<p><strong>Database</strong> ' . $db_name;
echo ' <strong>Login</strong> ' . $db_config->default['login'];
echo ' <strong>Password</strong> ' . $db_config->default['password'] . '</p>';
$sql = $_POST['sql'];


$connection = mysql_connect($db_config->default['host'],
    $db_config->default['login'],$db_config->default['password']);
$select = mysql_select_db($db_name,$connection);

if ($sql == "install.sql") {
$sqlfile = file("sql/".$sql);
$dump = "";

$user = $_POST['user'];
$pass = md5($_POST['pass']);
$emp = $_POST['emil'];
$firstname = $_POST['firstname'];
$surname = $_POST['lastname'];

foreach ($sqlfile as $line) {
if (trim($line)!='' && substr(trim($line),0,1)!='#') {
$line = str_replace("useradmin", $user, $line);
$line = str_replace("passadmin", $pass, $line);
$line = str_replace("thefirstname", $firstname, $line);
$line = str_replace("thesurname", $surname, $line);
$line = str_replace("prefix_", $prefix, $line);
$line = str_replace("email@yourdomain.com", $emp, $line);
$dump .= trim($line);
}
}

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
mysql_query($sql) or die('Error! Install Failed: ' . mysql_error());
}

$host = $_SERVER["SERVER_ADDR"];
$address = $_SERVER["HTTP_HOST"];
$address .= $_SERVER["PHP_SELF"];
$address = ereg_replace("/install/sql.php", "", $address);

$msg = "
Status Script Installed:
Address: http://$address
Host IP: $host
";

$msg = stripslashes($msg);

} else if ($sql) {

$sqlfile = file("sql/".$sql);
$dump = "";

foreach ($sqlfile as $line) {
if (trim($line)!='' && substr(trim($line),0,1)!='#') {
$line = str_replace("prefix_", $prefix, $line);
$dump .= trim($line);
}
}

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
mysql_query($sql) or die('Error! Update Failed: ' . mysql_error());
}

} else if (!$sql) {
echo "No SQL File Selected!";
}
echo '
<br>
<p>If no errors appeared above the script was installed successfully!<p>
<p><a href="index.php?page=4"><strong>Finish &gt;&gt;</strong></p>
';
} else if ($page == "4") {
copy('install.htaccess', '../.htaccess');
echo '
<p>Thanks for choosing SubwayCRM. <a href="../">Go to the login screen</a><p>
<p>You will no longer have access to the install screens. If you wish
to re-install SubwayCRM, then you will have to delete the .htaccess file
in your top level SubwayCRM directory.</p>
';
}

include("includes/footer.php");
?>
