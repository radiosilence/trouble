    <div id="kills">
      <?php
      $killboard->games->map(function($game){
        $game->kills->map(function($kill){ ?>
            <a href="agent/<?=$kill->assassin->alias?>" class="assassin"><?=$kill->assassin->alias?></a> kills <a class="target" href="agent/<?=$kill->target->alias?>"><?=$kill->target->alias?></a> with <a href="#" class="weapon"><?=$kill->weapon->name?></a> on <?=$kill->when_happened?><br/>
  <?php });
      });?>
        
    </div>