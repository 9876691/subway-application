<div id="logon-form">
	<h2>Welcome <?php echo $user['Person']['first_name']; ?>, please fill in the details below</h2>


	<?php if(! empty($errors)) { ?>
        <ul>
	<?php foreach ($errors as $error): ?>
	<li><?php echo $error; ?></li>
        <?php endforeach; ?>
        </ul>
        <?php } ?>

	<form method="post" action="<?php echo $html->url('accept')?>">
        <label>Select a username</label><br />
	<input type="text" id="username" name="username" /><br />
        <label>Select a password</label><br />
	<input type="password" name="password" /><br />
        <label>Confirm password</label><br />
	<input type="password" name="password1" /><br />
	<input type="submit" value="Accept invitation" />
	<input type="hidden" name="id" value="<?php echo $user['Person']['id']; ?>" />
	<input type="hidden" name="code" value="<?php echo $code; ?>" />
	</form>
</div>