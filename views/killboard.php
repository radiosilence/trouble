<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<base href="http://<?=HOST?><?=BASE_HREF?>/">
	<title>Killboard</title>
  </head>
  <body>
	<h1>Killboard</h1>
	<div id="kills">
	  <?php
	  $killboard->games->map(function($game){
	    $game->kills->map(function($kill){ ?>
	        <a href="agent/<?=$kill->assassin->id?>" class="assassin"><?=$kill->assassin->alias?></a> kills <a class="target" href="agent/<?=$kill->target->id?>"><?=$kill->target->alias?></a> with <a href="#" class="weapon"><?=$kill->weapon->name?></a> on <?=$kill->timestamp?><br/>
  <?php });
	  });?>
	    
	</div>
  </body>
</html>
