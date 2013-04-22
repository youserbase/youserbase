<div id="preconditions" class="rbox">
	<h3 class="accordion" for="preconditions"><phrase id="PRECONDITIONS"/></h2>
	<ul id="precondition_list">
		<?php foreach ($tables as $table):?>
			<li class="preconditions">
				<a class="lightbox" href="<?=FrontController::GetLink('presetlist', array('table' => $table))?>"><phrase id="<?=$table?>"/> (<?=$count[$table]?>)</a>
			</li>
		<?php endforeach;?>
	</ul>
</div>