<form id="agent_form" method="POST">
  <div class="<?=($new ? 'accordion' : 'tabs')?>">
    <?php if(!$new): ?>
    <input type="hidden" name="id" value="<?=$agent->id?>"/>
    <ul>
      <li><a href="#fieldset-contact">Contact Details</a></li>
      <li><a href="#fieldset-personal">Personal Data</a></li>
      <li><a href="#fieldset-security">Security</a></li>
    </ul>
    <?php endif;?>
    <?php if($new): ?>
    <h3><a href="#">Welcome</a></h3>
    <div class="fieldset" id="fieldset-welcome">
      <h1>Welcome</h1>
      <p class="intro">Welcome to trouble, the online system for organising assassins worldwide. So, agent. What would you like to be known as?</p>
      <p><label for="alias">Alias</label><br/>
      <input type="text" name="alias" id="alias" value="<?=$agent->alias?>"  placeholder="Ex: hunter2"/></p>
    </div>
    <?php endif;?>
    <?php if($new):?><h3><a href="#">Contact Details</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-contact">
      <h1>Contact Details</h1>
      <?php if($new): ?>
        <p class="intro">It is mandatory to supply your contact details. These must be filled out accurately and honestly, or your contract could suffer...termination.</p>
        <p class="intro">These details (other than full name) <em>cannot</em> be bought on the blackmarket. Your privacy is our highest concern. They may be accessible however for the purposes of organisation.</p>
      <?php endif;?>
      <p><label for="fullname">Full Name</label><br/>
      <input type="text" name="fullname" id="fullname" value="<?=$agent->fullname?>" placeholder="Ex: Jane Doe"/></p>
      <p><label for="email">E-Mail Address</label><br/>
      <input type="text" name="email" id="email" value="<?=$agent->email?>" placeholder="Ex: janedoe@gmail.com"/></p>
      <p><label for="phone">Telephone Number</label><br/>
      <input type="text" name="phone" id="phone" value="<?=$agent->phone?>" placeholder="Ex: +(44)1234 567890"/></p>
      <p><label for="address">Address</label><br/>
      <textarea name="address" id="address" placeholder="Ex. 123 Blah drive, Reading, RG2 1ZF"><?=$agent->address?></textarea></p>
    </div>
    <?php if($new):?><h3><a href="#">Personal Data</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-personal">
      <h1>Personal Data</h1>
      <?php if($new): ?>
        <p>In order to have a functioning blackmarket, one must have items to sell. This, agent, means your information - habits, courses, societies, recreation - all of this must be made available. If you are too cowardly to surrender this information, we would suggest playing <a href="http://www.neopets.com/">this</a> instead.</p>
        <p>NOTE: Without this, the game is pointless, and lacking or incorrect information is grounds for termination if joining a game that requires it.</p>
        <p>This information will stay internal to our system and will under no circumstance be sold to third parties or advertisers.</p>
        <p>This information will be available to game organisers.</p>
      <?php endif;?>
      <p>
      <label>Avatar</label> This can be any picture, it will be visible to others and cropped/resized.<br/>
        <img src="img/avatar/<?=$agent->avatar?>" id="avafile_img" style="display:<?=($agent->avatar ? 'block' : 'none')?>"/></p>
      <div id="avafile-uploader">    
        <noscript>      
          <p>Please enable JavaScript to use file uploader.</p>
        </noscript>         
      </div>
      <input type="hidden" name="avatar" id="avafile" value="<?=$agent->avatar?>"/>
      <p>
      <label>Photograph</label> This must be a clear, recognisable, recent picture of you.<br/>
        <img src="img/agent/<?=$agent->imagefile?>" id="imagefile_img" style="display:<?=($agent->imagefile ? 'block' : 'none')?>" class="photo"/></p>
      <div id="imagefile-uploader">    
        <noscript>      
          <p>Please enable JavaScript to use file uploader.</p>
        </noscript>         
      </div>
      <input type="hidden" name="imagefile" id="imagefile" value="<?=$agent->imagefile?>"/>
      <p><label for="course">Academic Course/Year (or Job, if not student)</label><br/>
      <input type="text" name="course" id="course" value="<?=$agent->course?>" placeholder="Ex: 3rd year/Computer Science (BSc)"/></p>
      <p><label for="clubs">Societies &amp; Groups</label><br/>
      <input type="text" name="societies" id="societies" value="<?=$agent->societies?>" placeholder="Ex: Rock, Rowing, Indie"/></p>
      <p><label for="clubs">Clubs &amp; Pubs</label><br/>
      <input type="text" name="clubs" id="clubs" value="<?=$agent->clubs?>" placeholder="Ex: The Sun, The Red Lion"/></p>
      <p><label for="timetable">Timetable (Detailed, may take some time)</label><br/>
      <textarea name="timetable" id="timetable" placeholder="Ex: Mon: 1200-1300 SSE G12, 1400-1600 Palmer 108; Tues: 0900-1000 Urban Studies LLT; Wed: 1000-1600 Starbucks, Riverside"><?=$agent->timetable?></textarea></p>
    </div>
    <?php if($new):?><h3><a href="#">Security</a></h3><?php endif;?>
    <div class="fieldset" id="fieldset-security">
      <h1>Security</h1>
      <?php if($new): ?>
        <p>Lastly, we'll need a password and captcha confirmation for your account.</p>
      <?php endif; ?>
      <p><label for="password">Password</label><br/>
      <input type="password" name="password" id="password" placeholder="<?=($new ? 'Ex: hunter2' : 'No change')?>"/></p>
      <p><label for="password_confirm">Password (Confirm)</label><br/>
      <input type="password" name="password_confirm" id="password_confirm" placeholder="<?=($new ? 'Ex: hunter2' : 'No change')?>"/></p>
      <?php if($new):?>
       Captcha goes here.
      <?php endif;?>
    </div>
  </div>
  <p class="submit"><button class="submit" id="submit_agent_form"><?=($new ? 'Process Application' : 'Save')?></button></p>
</form>
