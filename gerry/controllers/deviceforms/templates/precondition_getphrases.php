<ul>
<?php foreach($phrases as $phrase):?>
<?php if(!is_numeric($phrase)):?>
	<li>
		<?=$phrase?>
	</li>
<?php endif;?>
<?php endforeach;?>
</ul>