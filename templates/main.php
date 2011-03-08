<!DOCTYPE html>
<html>
  <head>
    <base href="http://<?=HOST?><?=BASE_HREF?>/">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="stylesheet" href="css_lib/reset.css" type="text/css"/>
    <link rel="stylesheet" href="css_lib/grid.css" type="text/css"/>
    <link rel="stylesheet" href="css_lib/fileuploader.css" type="text/css">
    <link rel="stylesheet" href="css_lib/subverse.css" type="text/css"/>
    <link rel="stylesheet" href="css/main.css" type="text/css"/>
    <script src="js_lib/jquery.js" type="text/javascript"></script>
    <script src="js_lib/jquery.cookie.js" type="text/javascript"></script>
<?php if(is_array($_jsapps)): foreach($_jsapps as $js) :?>
    <script src="js/<?=$js?>.js" type="text/javascript"></script>
<?php endforeach; endif; ?>
    <title><?=$title?> | Trouble</title>
  </head>
  <body class="<?=($user ? 'loggedin' : null)?>">
  <div class="container_12">
    <header class="grid_12">
      <h1><a href="http://<?=HOST?><?=BASE_HREF?>/">Trouble</a></h1>
    </header>
    <nav class="grid_12">
     <ul>
      <li><a href="news">News</a></li>
      <li><a href="games">Games</a></li>
      <li><a href="highscores">Highscores</a></li>
      <li><a href="forums">Forums</a></li>
      <li id="sign_up"><a href="sign-up">Sign Up</a></li>
     </ul>
    </nav>
    <section id="content" class="grid_9">
    <h1><?=$title?></h1>
    <?=$content?>
    </section>
    <section id="userbox" class="grid_3">
    <?=$_user_box?>
    </section>
  </div>
  </body>
</html>
