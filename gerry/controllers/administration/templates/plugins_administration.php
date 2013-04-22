<pre style="text-align: center; font: 14px monospace; border: 1px solid #444; background-color: #444; color: #aaa;"><?=md5(uniqid())?></pre>
<pre><?php var_dump($_POST); ?></pre>
<hr/>
<a href="<?=FrontController::GetLink()?>" class="ajax target:tab">
	Test
</a>

<form action="<?=FrontController::GetLink()?>" method="post" class="ajax target:tab">
	<input type="text" name="foo" value="<?=sprintf('%06d', mt_rand(0,100000))?>"/>
</form>


<div class="updateme" style="border: 1px solid black; padding: 5px; background-color: #ffc;">
	<span>
		<a href="<?=FrontController::GetLink()?>" class="ajax target:closest:.updateme">
			update me
		</a>
	</span>
</div>