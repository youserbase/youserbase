<div class="view translate">
<?php if (!empty($UNTRANSLATED)): ?>
	<ul style="display: none;">
	<?php foreach ($UNTRANSLATED as $phrase_id): ?>
		<li id="<?=md5($phrase_id)?>">
			<span rel="phrase#<?=$phrase_id?>"><?=$phrase_id?></span>
		</li>
	<?php endforeach; ?>
<?php endif; ?>
	</ul>
	<span <?=!empty($UNTRANSLATED)?'onclick="$(this).prev().toggle();"':''?>>
		Nicht übersetzt (<?=number_format(count($UNTRANSLATED),0,',','.')?>)
	</span>

<?php if (!empty($TRANSLATED)): ?>
	<ul style="display: none;">
	<?php foreach ($TRANSLATED as $phrase_id=>$translation): ?>
		<li id="<?=md5($phrase_id)?>">
			<span rel="phrase#<?=$phrase_id?>"><?=$translation?></span>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
	<span <?=!empty($TRANSLATED)?'onclick="$(this).prev().toggle();"':''?>>
		Übersetzt (<?=number_format(count($TRANSLATED),0,',','.')?>)
	</span>
</div>
