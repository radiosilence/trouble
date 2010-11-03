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
  <? $game->kills->map(function($kill){ ?>
    <tr>
      <th scope="row"><?=$kill->when_happened?></td>
      <td ><a href="agent/<?=$kill->assassin->alias?>"><?=$kill->assassin->alias?></a></td>
      <td ><a href="agent/<?=$kill->target->alias?>"><?=$kill->target->alias?></a></td>
      <td ><a href="  weapon/<?=$kill->weapon->id?>" class="weapon"><?=$kill->weapon->name?></a></td>
    </tr>
  <?php }); ?>
    </tbody>
  </table>
  </article>
 <?php });?>