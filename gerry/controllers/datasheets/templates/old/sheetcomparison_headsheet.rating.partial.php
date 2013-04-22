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
	</label>
</dt>
<dd>
<div class="ratingstar">
	<div style="width:<?=$rating?>%">
	</div>
</div>
</dd>