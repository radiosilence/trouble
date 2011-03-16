<form id="game_form" method="POST">
  <div <?=($new ? 'class="accordion"' : 'id="tabs_game_admin"')?>>
    <?php if(!$new): ?>
      <input type="hidden" name="id" value="<?=$game->id?>"/>
    <ul>
      <li><a href="#cpanel">Control Panel</a></li>
      <li><a href="#fieldset-info">Basic Information</a></li>
      <li><a href="#fieldset-dates">Dates</a></li>
      <li><a href="#fieldset-options">Options</a></li>
      <li><a href="#fieldset-description">Description</a></li>
    </ul>
    <?php endif;?>
    <?php if(!$new): ?>
    <div class="fieldset" id="cpanel">
        <h1>Control Panel</h1>
        <p>Here is where you can kick players, view the current order, and see the status of your game.</p>
        <?=$administration?>
    </div>
    <?php endif; ?>
    <?php if($new):?><h3><a href="#">Basic Information</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-info">
      <h1>Basic Information</h1>
      <?php if($new):?>
      <p class="intro">So you want to set up a game. First we need the name and location of the game.</p>
      <?php endif;?>
      <p><label for="name">Name</label><br/>
      <input type="text" name="name" id="name" value="<?=$game->name?>"  placeholder="Ex: My Awesome Game"/></p>
      <p><label for="location">Location</label><br/>
      <input type="text" name="location" id="location" value="<?=$game->location?>"  placeholder="Ex: Reading University, Berkshire, United Kingdom"/></p>
    </div>
    <?php if($new):?><h3><a href="#">Dates</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-dates">
      <h1>Dates</h1>
      <?php if($new):?>
      <p class="intro">When will the game be starting and ending?</p>
      <?php endif;?>
      <p><label for="start_date">Start Date</label><br/>
      <input type="text" name="start_date" id="start_date" class="datepick" value="<?=$game->start_date?>"  placeholder="Ex: 2012-02-09"/></p>
      <p><label for="end_date">End Date</label><br/>
      <input type="text" name="end_date" id="end_date" class="datepick" value="<?=$game->end_date?>"  placeholder="Ex: 2012-02-09"/></p>
    </div>
    <?php if($new):?><h3><a href="#">Options</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-options">
      <h1>Options</h1>
      <?php if($new):?>
      <p class="intro">This is where you configure the options for your game.</p>
      <?php endif;?>
      <p><label for="invite_only">Invitation Type</label>
      <div id="invite_only">
        <input type="radio" id="inv_open" name="invite_only"<?=($game->invite_only != 2 && $game->invite_only != 1 ? ' checked="checked"' : null)?> value="0"/><label for="inv_open">Open</label>
        <input type="radio" id="inv_voucher" name="invite_only"<?=($game->invite_only == 2 ? ' checked="checked"' : null)?> value="2"/><label for="inv_voucher">Entry Fee</label>
        <input type="radio" id="inv_password" name="invite_only"<?=($game->invite_only == 1 ? ' checked="checked"' : null)?> value="1"/><label for="inv_password">Password</label>
      </div></p>
      <p id="p_entry_fee"><label for="entry_fee">Entry Fee</label><br/>
      <strong>&pound; </strong><input type="text" name="entry_fee" id="entry_fee" class="price" value="<?=$game->entry_fee?>" placeholder="Ex: 2.50"/>&nbsp;&nbsp;<em>For informational value only &ndash; payments handled outside games.</em></p>
      <p id="p_password"><label for="password">Password</label><br/>
      <input class="password" name="password" id="password" placeholder="<?=($new ? 'Ex: hunter2' : 'No change')?>"/></p>
    </div>
    <?php if($new):?><h3><a href="#">Description</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-description">
      <h1>Description</h1>
      <p><label for="description" id="description">Description</label><br/>
      <textarea name="description" placeholder="Ex. This game is for students of my university"><?=$game->description?></textarea></p>
    </div>
  </div>
  <p class="submit"><button class="submit" id="submit_game_form"><?=($new ? 'Create Game' : 'Save')?></button></p>
</form>
