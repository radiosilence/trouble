<div class="tabs" id="tabs_game">
    <ul>
      <?php if($dashboard): ?>
      <li><a href="#dashboard">Dashboard</a></li>
      <?php endif; ?>
      <li><a href="#killboard">Killboard</a></li>
      <?php if($is_admin): ?>
      <li><a href="#administration">Administration</a></li>
      <?php endif; ?>
    </ul>

      <?php if($dashboard): ?>
        <div id="dashboard">
        <?=$dashboard?>
        </div>
      <?php endif;?>
    <div id="killboard">
    <?=$killboard?>
    </div>
    <?php if($is_admin): ?>
      <div id="administration">
      <?=$administration?>
      </div>
    <?php endif; ?>
</div>