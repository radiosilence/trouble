<p>Here is where you control a game that you administrate.</p>
<article>
  <h1>Players</h1>
  <?php foreach(array(1 => 'Alive', 0 => 'Dead') as $s => $t):?>
  <h2><?=$t?></h2>
  <ul>
    <?php foreach($all_players->filter($s, 'status') as $player): ?>
    <li><a href="agent/<?=$player->agent->alias?>#edit"><?=$player->agent->alias?></a> (PKN: <strong><?=$player->pkn?></strong>)</li>
    <?php endforeach; ?>
  <ul>
  <?php endforeach; ?>