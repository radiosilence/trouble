<div id="games">
<?php if(count($games) > 0): ?>
<?php foreach($games as $game):
?>

  <article>
    <h3>Ending <?=$game->end_date->format("Ymd.Hi")?></h3>
    <h1><a href="game/<?=$game->id?>"><?=$game->name?></a>
    <?php if($game->joinable && !$game->joined && $logged_in):?>&nbsp;<button class="join_game" game_id="<?=$game->id?>">Join</button><?php endif; ?></h1>
    <h2><?=$game->location?></h2>
    <p><?php if($game->num_players > 0):?> <em>(<?=$game->num_players?> players)</em><?php endif;?> <?=($game->joined ? 'You have joined this game.' : null) ?></p>
    <p><?=$game->description?></p>
    <ul>
    <?php if($game->joined): ?><li><a href="game/<?=$game->id?>#dashboard">Dashboard</a></li><?php endif;?>
    <li><a href="game/<?=$game->id?>#killboard">Killboard</a></li>

    </ul>
  </article>
<?php endforeach; ?>
<?php else: ?>
<p>There are no games to display.</p>
<?php endif;?>
</div>