<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<base href="http://<?php echo HOST?><?php echo BASE_HREF ?>/">
	<title><?php echo $page_title?> | <?php echo $site_name?></title>
  </head>
  <body>
	<h1><?php echo $page_title?></h1>
	<div id="kills">
	<?php foreach( $games as $gid => $kills ): ?>
	  <?php if( count( $games ) > 1 ): ?>
	  Game <?php echo $gid ?>
	  <hr/>
	  <?php endif; ?>
	  <?php foreach( $kills as $kill ): ?>
	    <a href="view/agent/id:<?php echo $kill[ "aid" ]?>" class="assassin"><?php echo $kill[ "assassin" ]?></a> kills <a class="target" href="view/agent/id:<?php echo $kill[ "tid" ]?>"><?php echo $kill[ "target" ]?></a> with <a href="#" class="weapon"><?php echo $kill[ "weapon" ]?></a> on <?php echo $kill[ "timestamp" ]?><br/>
          <?php endforeach; ?>
	<?php endforeach; ?>
	</table>
  </body>
</html>