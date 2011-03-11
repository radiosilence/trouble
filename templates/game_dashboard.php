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
<p><button id="register_kill">Register Kill</a></p>
<?php endif;?>
</article>

<div id="dialog-form" title="Register Kill">
	<form>
	<fieldset>
		<p><label for="time">Time</label><br/>
		<input type="text" name="when_happened[1]" id="time" class="time" placeholder="Ex. 21:35"/>&nbsp;&nbsp;24-hour format. Give closest estimate.</p>
		<p><label for="date">Date</label><br/>
		<input type="text" name="when_happened[0]" id="date" placeholder="Ex. 2011-03-10"/></p>
		<p><label for="weapon">Weapon</label><br/>
		<select name="weapon" id="weapon">
			<option value="0">Select weapon used...</option>
		</select></p>
		<p><label for="description">Tell the Story...</label><br/>
		<textarea name="description" id="description" placeholder="Ex. I spotted my target and shot her in the face"></textarea></p>
	</fieldset>
	</form>
</div>