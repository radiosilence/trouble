<article>
<img src="/img/game/<?=($game->imagefile ? $game->imagefile : 'default.png')?>" class="itempic"/>
 <h1><?=$game->name?></h1>
 <p><code>State: <?=$states[$game->state]?></code></p>
 <h2>Takes place from <?=$game->start_date->format(DATE_FORMAT)?> until <?=$game->end_date->format(DATE_FORMAT)?>.</h2>
    <p class="game_description"><?=$game->description?></p>
 <?php if($game->joinable && !$joined && $logged_in):?>
    &nbsp;<button class="join_game" game_id="<?= $game->id?>" invite_only="<?=$game->invite_only?>">Join</button>&nbsp;
    <?php if($game->invite_only == 1) {
        echo "Password only";
    } else if($game->invite_only == 2) {
        echo "Entry fee: <strong>&pound;{$game->entry_fee}</strong>";
    } ?>
 <?php elseif(!$game->joinable && !$joined && $logged_in): ?>
    <p>You cannot join this game as it is in progress or ended.</p>
 <?php endif;?>
</article>
<div style="display: none"><?=$_join_dialogs?></div>