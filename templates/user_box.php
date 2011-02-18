<h1><?=$user->alias?></h1>
<form action="logout" method="post">
<input type="hidden" name="uri" value="<?=$__uri__?>"/>
<p><button class="submit">Log Out</button></p>
</form>