<article>
<?php if($player->status == 0): ?>
<p>You are dead.</p>
<p>Killed by: <a href="agent/<?=$kill->assassin->alias?>"><?=$kill->assassin->alias?></a> at <strong><?=$kill->when_happened->format('Ymd.Hi')?></strong> with <strong><?=$kill->weapon->name?></strong></p>
<p>
<button id="dispute_kill">Dispute Kill</button>
</p>

<?php elseif($self_target):?>
<p>You are your own target. This means either that you have won (yay!) or you are currently the only player in the game.</p>
<?php else: ?>
<h1>Current Target</h1>
<p><a class="target" href="agent/<?=$target["alias"]?>"><?=$target["alias"]?></a></p>
<p><button id="register_kill" game_id="<?=$game->id?>">Register Kill</a></p>
<?php endif;?>
</article>

<div id="dialog-form" title="Register Kill">
	<form>
	<fieldset>
		<p><label for="weapon">Weapon</label><br/>
		<select name="weapon" class="default" id="weapon">
			<option value="0" class="dropdown_default">Select weapon used...</option>
		</select></p>
		<p><label for="time">Time/Date</label><br/>
		<input type="text" name="when_happened_time" id="when_happened_time" class="timepick" placeholder="Ex. 21:35"/>&nbsp;<input type="text" name="when_happened_date" id="when_happened_date" class="datepick" placeholder="Ex. 2011-03-10"/></p>
		<p><label for="description">Tell the Story...</label><br/>
		<textarea name="description" id="description" style="height: 120px;" placeholder="Ex. I spotted my target and shot her in the face"></textarea></p>
	</form>
	</fieldset>
</div>