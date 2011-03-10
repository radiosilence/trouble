<div id="games"><?php foreach($games as $game):
?>

  <article>
    <h3>Ending <?=$game->end_date->format("Ymd.Hi")?></h3>
    <h1><?=$game->name?>
    <?php if($game->joined):?>&nbsp;<button class="leave_game" game_id="<?=$game->id?>">Leave</button>
    <?php elseif($game->joinable && !$game->joined && $logged_in):?>&nbsp;<button class="join_game" game_id="<?=$game->id?>">Join</button><?php endif; ?></h1>
    <h2><?=$game->location?></h2>
    <p><?=($game->joined ? 'You have joined this game.' : null) ?></p>
    <p><?=$game->description?></p>
    <ul>
    <li><a href="game/<?=$game->id?>"><?=($game->joined ? 'Dashboard' : 'Information')?></a></li>
    <li><a href="game/<?=$game->id?>/killboard">Killboard</a></li>

    </ul>
  </article>
<?php endforeach; ?>
</div>