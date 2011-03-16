<div class="tabs">
    <ul>
      <?php if($dashboard): ?>
      <li><a href="#dashboard">Dashboard</a></li>
      <?php else: ?>
      <!--<li><a href="#information">Information</a></li>-->
      <?php endif;?>
      <li><a href="#killboard">Killboard</a></li>
    </ul>

      <?php if($dashboard): ?>
        <div id="dashboard">
        <?=$dashboard?>
        </div>
      <?php endif;?>
    <div id="killboard">
    <?=$killboard?>
    </div>
</div>