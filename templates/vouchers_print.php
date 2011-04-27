<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    
* {
    font-family: Helvetica, Arial, sans-serif;
}
h1 {
    margin: 0;
    font-size: 20pt;
}
code {
    font-family: "Consolas", "Monaco", "Lucida Console", monospace;
    font-weight: bold;
    font-size: 14pt;
    padding: 0em 1em;
}

td.text {
    padding-left: 1em;
}
</style></head>
<body>
<table border="0" style="width: 100%">
<tr>
<?php $i = 0;
 foreach($vouchers as $voucher): ?>
 <td>
 <table style="border: 1px solid black; width:100%">
  <tr>
    <td rowspan="3" width="1"><img src="/img/game/<?=($voucher->game->imagefile ? $voucher->game->imagefile : 'default_print.png')?>"/></td>
    <td class="text"><h1><?=$voucher->game->name?></h1></td>
  </tr>
  <tr>
   <td class="text"><?=$types[$voucher->type]?></td>
  </tr>
  <tr>
   <td class="text"><strong>Code: </strong><code><?=$voucher->code?></code></td>
  </tr>
 </table>
 </td>
<?=(++$i % 2 == 0 ? '</tr><tr>' : null)?>
<?php endforeach; ?>
</table>
</body>
</html>