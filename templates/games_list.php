<div id="games">
<?php if(count($games) > 0): ?>
<?php foreach($games as $game):
?>

  <article>
    <div class="listcol1"><img src="/img/game/<?=($game->imagefile ? $game->imagefile : 'default.png')?>" class="itempic"/></div>
    <div class="listcol2"><h3>Active from <?=$game->start_date->format(DATE_FORMAT)?> until <?=$game->end_date->format(DATE_FORMAT)?>.</h3>
    <h1 class="gamelist"><a href="game/<?=$game->id?>"><?=$game->name?></a>
        <?php if($game->joinable && !$game->joined && $logged_in):?>
            &nbsp;<button class="join_game" game_id="<?=$game->id?>" invite_only="<?=$game->invite_only?>">Join</button>&nbsp;<span class="joininfo">
        <?php if($game->invite_only == 1) {
            echo "Password required";
        } else if($game->invite_only == 2) {
            echo "Entry fee of <strong>&pound;{$game->entry_fee}</strong>";
        } ?></span>
        <?php endif; ?>
    </h1>
    <h2><?=$game->location?></h2>
    <p><code>State: <?=$states[$game->state]?></code></p>
    <p class="game_description"><?=$game->description?></p>
    </div>
  </article>
<?php endforeach; ?>
<div style="display: none"><?=$_join_dialogs?></div>
<?php else: ?>
<p>There are no games to display.</p>
<?php endif;?>
</div>