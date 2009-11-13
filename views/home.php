<!DOCTYPE html>
<html>
  <head>
	<title><?php echo $page_title?> | <?php echo $site_name?></title>
  </head>
  <body>
	<h1><?php echo $page_title?></h1>
	<p>Asd</p>
	<div id="kills">
	<table>
	<?php foreach( $kills as $kill ): ?>
	    <tr>
		<td><?php echo $kill[ "name" ]?></td>
	    </tr>
	<?php endforeach; ?>
	</table>
  </body>
</html>