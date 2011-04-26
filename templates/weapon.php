<article><img src="/img/weapon/<?=($weapon->imagefile ? $weapon->imagefile : strtolower($weapon->class) . '.png')?>" class="itempic"/>
<h1><?=$weapon->name?></h1>
<h2><?=$weapon->class?></h2>
<p><?=nl2br($weapon->description);?></p>
</article>