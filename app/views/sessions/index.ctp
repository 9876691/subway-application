<div id="logon-form">
    <h2>Login</h2>
    <form method="post" action="<?php echo $html->url('index')?>">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" /><br>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" /><br><br>
        <input type="submit" value="Login" />
    </form>
</div>
