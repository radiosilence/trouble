<img src="/img/avatar/<?=($user->avatar ? $user->avatar : 'default.png')?>" style="float: right;"/>
<h1><?=$user->alias?></h1>
<form action="logout" method="post">
<input type="hidden" name="uri" value="<?=$__uri__?>"/>
<p><ul>
 <li><a href="edit-profile">Edit Profile</a></li>
 <li><a href="your-games">Your Games</a></li>
</ul></p>
<p><button id="logout">Log Out</button></p>
</form>