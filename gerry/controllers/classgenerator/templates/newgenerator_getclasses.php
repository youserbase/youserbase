<div class="rbox">
<h3><phrase id="Klassengenerator"/></h3>
<?php if(isset($preset)):?>
<?php foreach ($preset as $table => $table_description):?>
	
	<div><?=$table?>
		<div>

			<?php foreach ($table_description as $line => $l):?>
			<?php if ($l == "\r\n"):?>
				<br>
			<?php else:?>
				<?=$l?>
			<?php endif;?>
			<?php endforeach;?>
		</div>
	</div>
	
<?php endforeach;?>
<?php endif;?>
</div>