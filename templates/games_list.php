<div id="games"><?php $games->map(function($game) use($__xsrf__) {
?>
  <article>
    <h3>Ending <?=$game->end_date?></h3>
    <h1><?=$game->name?></h1>
    <h2><?=$game->location?></h2>
    <p><?=$game->description?></p>
    <nav>
    [ <a href="game/<?=$game->id?>/killboard<?=$__xsrf__?>">Killboard</a> ] 
    <?php if($game->state<1):?>[ <a href="game/<?=$game->id?>/join<?=$__xsrf__?>">Join</a> ]<?php endif; ?>
    </nav>
  </article>
<?php }); ?>
</div>