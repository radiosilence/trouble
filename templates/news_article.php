<p><a href="news">Back to News</a></p>
<article>
<h1><?=$article->title?></h1>
<p><em><?=$article->posted_on->format(DATE_FORMAT)?></em></p>
<p><?=$article->body_html?></p>
</article>