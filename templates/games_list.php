<div id="games"><?php $games->map(function($game) {
?>
  <article>
    <h3>Ending <?=$game->end_date->format("Ymd.Hi")?></h3>
    <h1><?=$game->name?><?php if($game->joinable && !$game->joined):?>&nbsp;<button class="join_game" game_id="<?=$game->id?>">Join</button><?php endif; ?></h1>
    <h2><?=$game->location?></h2>
    <p><?=($game->joined ? 'You have joined this game.' : null) ?></p>
    <p><?=$game->description?></p>
    <nav>
    [ <a href="game/<?=$game->id?>/killboard">Killboard</a> ]
    </nav>
  </article>
<?php }); ?>
</div>