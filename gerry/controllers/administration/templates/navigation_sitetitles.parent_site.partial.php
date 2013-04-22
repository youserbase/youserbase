<?php if (empty($parent_sites[$index])): ?>
	<span rel="<?=$index?>" class="parent_selector">- keine Oberseite -</span>
<?php else: ?>
	<?php foreach ($sites as $module=>$controllers): ?>
		<?php foreach ($controllers as $controller=>$methods): ?>
			<?php foreach ($methods as $m_index=>$method): ?>
				<?php if ($m_index==$parent_sites[$index]): ?>
				<span rel="<?=$index?>" class="parent_selector"><?=$module?>/<?=$controller?>/<?=$method?></span>
				<input type="hidden" name="parent_sites[<?=$index?>]" value="<?=$m_index?>"/>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endif; ?>