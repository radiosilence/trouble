<div id="games">
<?php if(count($games) > 0): ?>
<?php foreach($games as $game):
?>

  <article>
    <img src="/img/game/<?=($game->imagefile ? $game->imagefile : 'default.png')?>" class="itempic"/>
    <h3>Ending <?=$game->end_date->format(DATETIME_FORMAT)?></h3>
    <h1><a href="game/<?=$game->id?>"><?=$game->name?></a>
        <?php if($game->joinable && !$game->joined && $logged_in):?>
            &nbsp;<button class="join_game" game_id="<?=$game->id?>" invite_only="<?=$game->invite_only?>">Join</button>
        <?php endif; ?>
    </h1>
    <h2><?=$game->location?></h2>
    <p><code>State: <?=$states[$game->state]?></code></p>
    <p class="game_description"><?=$game->description?></p>

  </article>
<?php endforeach; ?>
<div style="display: none"><?=$_join_dialogs?></div>
<?php else: ?>
<p>There are no games to display.</p>
<?php endif;?>
</div>