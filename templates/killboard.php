<?php $killboard->games->map(function($game){ ?>
  <article class="killboard">
  <h1>Killboard</h1>
  <table class="kills">
    <thead>
      <th class="date">Date</th>
      <th class="assassin">Assassin</th>
      <th class="victim">Victim</th>
      <th class="weapon">Weapon</th>
    </thead>
    <tbody>
<?php if($game->kills->count() > 0) { 
    foreach($game->kills as $kill) { ?>
    <tr>
      <th scope="row" class="date"><?=$kill->when_happened->format(DATETIME_FORMAT)?></nobr></td>
      <td><a href="/game/<?=$game->id?>/intel/<?=$kill->assassin->alias?>"><nobr><?=$kill->assassin->alias?></nobr></a></td>
      <td><a href="/game/<?=$game->id?>/intel/<?=$kill->target->alias?>"><nobr><?=$kill->target->alias?></nobr></a></td>
      <td><a href="weapon/<?=$kill->weapon->id?>" class="weapon"><nobr><?=$kill->weapon->name?></nobr></a></td>
    </tr>
  <?php } 
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