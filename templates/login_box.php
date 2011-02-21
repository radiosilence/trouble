<h1>Login</h1>
<form action="login" method="post">
<input type="hidden" name="uri" value="<?=$__uri__?>"/>
<p><input type="text" name="username" id="login_username" placeholder="Alias"/></p>
<p><input type="password" name="password" id="login_password" placeholder="Password"/></p>
<p><?=$msg_login?></p>
<p><button class="submit">Log In</button></p>
</form>