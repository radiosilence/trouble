<script src="js_lib/fileuploader.js" type="text/javascript"></script>
<script>        
    jQuery(function(){      
        var uploader = new qq.FileUploader({
            element: document.getElementById('imagefile-uploader'),
            action: 'action/save_agent_image',
            onComplete: function(id, fileName, responseJSON){
              var fn = responseJSON['filename']+'.'+responseJSON['ext'];
              $('#imagefile_img').attr('src','img/agent/'+fn);
              $('#imagefile_img').css('display','block');
              $('#imagefile').val(fn);
            },
            debug: true
        });           
    });     
</script>
<form id="agent"<?=($new ? ' class="wizard"' : null)?> method="POST" action="agent/<?=($new ? 'create' : $agent->alias . '/edit' )?>">
  <input type="hidden" name="submitted" value="1"/>
  <?php if(!$new): ?>
    <input type="hidden" name="id" value="<?=$agent->id?>"/>
  <?php endif;?>
  <?php if(count($errors) > 0):?>
    <ul class="error">
    <?php foreach($errors as $error): ?>
      <li><?=$error?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif;?>
  <?php if($new): ?>
  <fieldset id="1">
    <h1>Welcome</h1>
    <p class="intro">Welcome to trouble, the online system for organising assassins worldwide. So, agent. What would you like to be known as?</p>
    <p><label for="alias">Alias<br/>
    <input type="text" name="alias" id="alias" value="<?=$agent->alias?>"  placeholder="Ex: hunter2"/></p>
  </fieldset>
  <?php endif;?>
  <fieldset id="2">
    <h1>Contact Details</h1>
    <?php if($new): ?>
      <p class="intro">It is mandatory to supply your contact details. These must be filled out accurately and honestly, or your contract could suffer...termination.</p>
      <p class="intro">These details (other than full name) <em>cannot</em> be bought on the blackmarket. Your privacy is our highest concern. They may be accessible however for the purposes of organisation.</p>
    <?php endif;?>
    <p><label for="fullname">Full Name<br/>
    <input type="text" name="fullname" id="fullname" value="<?=$agent->fullname?>" placeholder="Ex: Jane Doe"/></p>
    <p><label for="email">E-Mail Address<br/>
    <input type="text" name="email" id="email" value="<?=$agent->email?>" placeholder="Ex: janedoe@gmail.com"/></p>
    <p><label for="phone">Telephone Number<br/>
    <input type="text" name="phone" id="phone" value="<?=$agent->phone?>" placeholder="Ex: +(44)1234 567890"/></p>
    <p><label for="address">Address</label><br/>
    <textarea name="address" id="address" placeholder="Ex. 123 Blah drive, Reading, RG2 1ZF"><?=$agent->address?></textarea></p>
  </fieldset>
  <fieldset id="3">
    <h1>Personal Data</h1>
    <?php if($new): ?>
      <p>In order to have a functioning blackmarket, one must have items to sell. This, agent, means your information - habits, courses, societies, recreation - all of this must be made available. If you are too cowardly to surrender this information, we would suggest playing <a href="http://www.neopets.com/">this</a> instead.</p>
      <p>NOTE: Without this, the game is pointless, and lacking or incorrect information is grounds for termination if joining a game that requires it.</p>
      <p>This information will stay internal to our system and will under no circumstance be sold to third parties or advertisers.</p>
    <?php endif;?>
    <p>
    <label for="imagefile">Photograph<br/>
      <img src="img/agent/<?=$agent->imagefile?>" id="imagefile_img" style="display:<?=($agent->imagefile ? 'block' : 'none')?>"/>
    <div id="imagefile-uploader">    
      <noscript>      
        <p>Please enable JavaScript to use file uploader.</p>
      </noscript>         
    </div>
    <input type="hidden" name="imagefile" id="imagefile" value="<?=$agent->imagefile?>"/></p>
    <p><label for="course">Academic Course/Year<br/>
    <input type="text" name="course" id="course" value="<?=$agent->course?>" placeholder="Ex: 3rd year/Computer Science (BSc)"/></p>
    <p><label for="clubs">Clubs/Societies<br/>
    <input type="text" name="clubs" id="clubs" value="<?=$agent->clubs?>" placeholder="Ex: Rock, Rowing, Indie"/></p>
    <p><label for="timetable">Timetable (Detailed, may take some time)</label><br/>
    <textarea name="timetable" id="timetable" placeholder="Ex: Mon: 1200-1300 SSE G12, 1400-1600 Palmer 108; Tues: 0900-1000 Urban Studies LLT; Wed: 1000-1600 Starbucks, Riverside"><?=$agent->timetable?></textarea></p>
  </fieldset>
  <fieldset>
    <?php if($new): ?>
      <p>Lastly, we'll need a password and captcha confirmation for your account.</p>
    <?php endif; ?>
    <p><label for="password">Password<br/>
    <input type="password" name="password" id="password"/></p>
    <p><label for="password_confirm">Password (Confirm)<br/>
    <input type="password" name="password_confirm" id="password_confirm"/></p>
    <?php if($new):?>
     Captcha goes here.
    <?php endif;?>
  </fieldset>
  <p class="submit"><button class="submit"><?=($new ? 'Process Application' : 'Save')?></button></p>
</form>