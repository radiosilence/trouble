<p>Here is where you control a game that you administrate.</p>
<article>
  <h1>Players</h1>
  <h2>Alive</h2>
  <ul>
    <?php foreach($all_players->filter(1, 'status') as $player): ?>
    <li><?=$player->agent->alias?></li>
    <?php endforeach; ?>
  <ul>
  <h2>Dead</h2>
  <ul>
    <?php foreach($all_players->filter(0, 'status') as $player): ?>
    <li><?=$player->agent->alias?></li>
    <?php endforeach; ?>
  <ul>