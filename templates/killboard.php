<?php $killboard->games->map(function($game){ ?>
  <article class="killboard">
  <table class="kills">
    <thead>
      <th>Date</th>
      <th>Assassin</th>
      <th>Victim</th>
      <th>Weapon</th>
    </thead>
    <tbody>
<?php if($game->kills->count() > 0) { 
    $game->kills->map(function($kill){ ?>
    <tr>
      <th scope="row"><?=$kill->when_happened->format('Ymd.Hi')?></td>
      <td><a href="agent/<?=$kill->assassin->alias?>"><?=$kill->assassin->alias?></a></td>
      <td><a href="agent/<?=$kill->target->alias?>"><?=$kill->target->alias?></a></td>
      <td><a href="weapon/<?=$kill->weapon->id?>" class="weapon"><?=$kill->weapon->name?></a></td>
    </tr>
  <?php }); 
  } else { ?>
    <tr>
    <td colspan="4" class="message">
      There have been no kills yet this game.
    </td>
  <?php } ?>
    </tbody>
  </table>
  </article>
 <?php });?>