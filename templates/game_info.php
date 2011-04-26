<article>
 <h1><?=$game->name?></h1>
 <?php if($game->state == 0): ?>
    <h3>Starts <?=$game->start_date->format("Ymd.Hi")?></h3>
 <?php endif; ?>
 <h3>Ends <?=$game->end_date->format("Ymd.Hi")?></h3>
 <p><?=$game->description?></p>
 <p>
   <?php if($game->num_players > 0):?>
     <em>(<?=$game->num_players?> players)</em>
   <?php endif;?>
 </p>
 <?php if($game->joinable && !$game->joined && $logged_in):?>
    &nbsp;<button class="join_game" game_id="<?= $game->id?>" invite_only="<?=$game->invite_only?>">Join</button>
<?php endif; ?>
</article>
<div style="display: none"><?=$_join_dialogs?></div>