<div class="rbox">
	<h3>
		<phrase id="Klassengenerator"/>
	</h3>
	<div>
		<a href="<?=FrontController::GetLink('newGenerator', 'getClasses')?>">Starten</a>
		<div class="status">
			<?php if(!empty($count)):?>
				<?=$count?> <?=($count!=0)?'Preset-Tabellen erzeugt':''?>
			<?php endif;?>
			<?php if(!empty($count_classes)):?>
				<?=$count_classes?> <?=($count_classes!=0)?'Tabellen erzeugt':''?>
			<?php endif;?>
		</div>
	</div>
	<div>
		<div class="test">
			<a href="<?=FrontController::GetLink('Test')?>">Testen der Funktion</a>
		</div>
	</div>
</div>