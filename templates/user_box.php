<h1><?=$user->alias?></h1>
<form action="logout" method="post">
<input type="hidden" name="uri" value="<?=$__uri__?>"/>
<p><ul>
 <li><a href="edit-profile">Edit Profile</a></li>
</ul></p>
<p><button>Log Out</button></p>
</form>