
<?php if($player->status == 0): ?>
<article>
<p>You are dead.</p>
<p>Killed by: <a href="agent/<?=$kill->assassin->alias?>"><?=$kill->assassin->alias?></a> at <strong><?=$kill->when_happened->format('Ymd.Hi')?></strong> with <strong><?=$kill->weapon->name?></strong></p>
<p>
<button id="dispute_kill">Dispute Kill</button>
</p>
</article>
<?php elseif($self_target):?>
<article>
<p>You are your own target. This means either that you have won (yay!) or you are currently the only player in the game.</p>
</article>
<?php else: ?>
<article>
<h1>Player Kill Number</h1>
<p>You <strong>must</strong> give this number to someone you believe has fairly killed you, as it is needed to register a kill. Failure to tell a killer your PKN is considered cheating. Your PKN is unique to the game you are in.</p>
<p>Your PKN: <span class="pkn"><?=$player->pkn?></span></p>
</article>
<article>
<h1>Current Target</h1>
<p><a class="target" href="agent/<?=$target["alias"]?>"><?=$target["alias"]?></a>&nbsp;&nbsp;<button id="register_kill" game_id="<?=$game->id?>">Register Kill</button></p>
</article>
<?php endif;?>

<div id="dialog-form" title="Register Kill">
	<form>
	<fieldset>
		<p><label for="pkn">PKN</label><br/>
			<input type="text" name="pkn" id="pkn" placeholder="xxxx"/></p>
		<p><label for="weapon">Weapon</label><br/>
		<select name="weapon" class="default" id="weapon">
			<option value="0" class="dropdown_default">Select weapon used...</option>
		</select></p>
		<p><label for="time">Time/Date</label><br/>
		<input type="text" name="when_happened_time" id="when_happened_time" class="timepick" placeholder="Ex. 21:35"/>&nbsp;<input type="text" name="when_happened_date" id="when_happened_date" class="datepick" placeholder="Ex. 2011-03-10"/></p>
		<p><label for="description">Tell the Story...</label><br/>
		<textarea name="description" id="description" placeholder="Ex. I spotted my target and shot her in the face"></textarea></p>
	</form>
	</fieldset>
</div>