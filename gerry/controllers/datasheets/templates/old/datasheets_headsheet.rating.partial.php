<dt>
	<label for="<?=$name?>"><phrase id="<?=strtoupper($name)?>"/>
	<?php if(isset($count)):?>
		(<?=$count?>
		<?php if($count > 1):?>
			<phrase id="RATINGS"/>)
		<?php else:?>
			<phrase id="RATING"/>)
		<?php endif;?>
	<?php endif;?>
	<?php if ($name == 'device_rating'):?>
		<img class="toggle_ratings" src="<?=Assets::Image('famfamfam/bullet_toggle_minus.png')?>" alt="collapse" title_phrase="COLLAPSE"/>
		<img class="toggle_ratings" src="<?=Assets::Image('famfamfam/bullet_toggle_plus.png')?>" alt="expand" style="display:none" title_phrase="EXPAND"/>
	<?php endif;?>
	</label>
</dt>
<dd>
<div class="ratingstar">
	<div style="width:<?=$rating?>%">
	</div>
</div>
</dd>
