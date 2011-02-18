<?php foreach($articles as $article): ?>
<article>
<h1><?=$article->title?></h1>
<p><em><?=$article->posted_on->format("Ymd.Hi")?></em></p>
<p><?=$article->preview?>...</p>
<p><a href="news/<?=$article->id?>/<?=$article->seo_title?>">Read More...</a></p>
</article?>
<?php endforeach; ?>
