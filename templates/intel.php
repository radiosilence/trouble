<article>
<h1>Intel</h1>
<p><button id="buy_intel" game_id="<?=$game->id?>" subject_id="<?=$agent->id?>">Buy Intel</button></p>
<?php if(count($owned_intels) > 0): ?>
  <?php foreach($owned_intels as $intel): ?>
    <h2><?=$intel['intel']['name']?></h2>
    <?php if($intel['intel']['field'] == 'imagefile'): ?>
      <p><img src="/img/agent/<?=$intel['data']?>"/></p>
    <?php else: ?>
      <p><?=nl2br($intel['data'])?></p>
    <?php endif;?>
  <?php endforeach; ?>
<?php else: ?>
  <p>You have not bought any intel on this agent.</p>
<?php endif;?>
</article>
<div id="dialog-intel" title="Buy Intel">
  <form>
    <p>Balance: <span class="credits"><?=$player['credits']?></span></p>
    <p><label for="intel">Intel</label><br/>
      <select name="intel" class="default" id="intel">
        <option value="0" class="dropdown_default">Select intel desired...</option>
      </select>
    </p>
    <p id="inteldescription"></p>
  </form>
</div>