<!DOCTYPE html>
<html>
  <head>
    <base href="http://<?=HOST?><?=BASE_HREF?>/">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="css_lib/grid.css" type="text/css"/>
    <link rel="stylesheet" href="css_lib/fileuploader.css" type="text/css"> 
    <link rel="stylesheet" href="css/main.css" type="text/css"/>
    <script src="js_lib/jquery.js" type="text/javascript"></script>
    <title><?=$title?> | Trouble</title>
  </head>
  <body>
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
      <li><a href="agent/create">Create Agent</a></li>
     </ul>
    </nav>
    <section id="content" class="grid_10">
    <h1><?=$title?></h1>
    <?=$content?>
    </section>
    <section id="fuck" class="grid_2">
    <p>Think of something useful to put here.</p>
    </section>
  </div>
  </body>
</html>