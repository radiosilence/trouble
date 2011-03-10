<article>
<?php if($self_target):?>
<p>You are your own target. This means either that you have won (yay!) or you are currently the only player in the game.</p>
<?php else: ?>
<h1>Current Target</h1>
<p><a class="target" href="agent/<?=$target["alias"]?>"><?=$target["alias"]?></a></p>
<?php endif;?>
</article>