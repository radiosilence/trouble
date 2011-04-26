<?php if($player->status == 0): ?>
	<article>
	<p>You are dead.</p>
	<p>Killed by: <a href="/game/<?=$game->id?>/intel/<?=$kill->assassin->alias?>"><?=$kill->assassin->alias?></a> at <strong><?=$kill->when_happened->format('Ymd.Hi')?></strong> with <strong><?=$kill->weapon->name?></strong>
	<button id="dispute_kill">Dispute Kill</button>
	</p>
	</article>
<?php elseif($self_target):?>
	<article>
	<p>You are your own target. This means either that you have won (yay!) or you are currently the only player in the game.</p>
	</article>
<?php elseif($player->status == 1): ?>
	<article>
	<h1>Player Kill Number</h1>
	<p>You <strong>must</strong> give this number to someone you believe has fairly killed you, as it is needed to register a kill. Failure to tell a killer your PKN is considered cheating. Your PKN is unique to the game you are in.</p>
	<p>Your PKN: <span class="pkn"><?=$player->pkn?></span></p>
	</article>
	<?php if($game['active']): ?>
	<article>
	<h1>Current Target</h1>
	<p><a class="target" href="/game/<?=$game->id?>/intel/<?=$target["alias"]?>"><?=$target["alias"]?></a>&nbsp;<button id="register_kill" game_id="<?=$game->id?>">Register Kill</button></p>
	</article>
	<?php endif;?>
<?php else: ?>
	<article>
	<p>You have left this game and are no longer in the cycle.</p>
	</article>
<?php endif;?>
<article>
<h1>Balance</h1>
Credits: <?=$player->credits?>&nbsp;&nbsp;<button>Buy Credits</button>
</article>
<article>
<h1>Leave Game</h1>
<p>Note that if the game you are leaving is in progress or ended, you <strong>cannot</strong> rejoin! This is akin to forfeiting.</p>
<p><button class="leave_game" game_id="<?=$game->id?>">Leave</button></p>
</article>
<?=$_dialog_kill?>