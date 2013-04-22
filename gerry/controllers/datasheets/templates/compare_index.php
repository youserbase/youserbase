<?php
	if(count($device_ids) > 1)
	{
		$this->assign('PREPEND_CONTENT', $this->render_partial('comparetabheader', compact('sheet', 'tab', 'compare_id')));

	}
	else
	{
		$this->assign('page_title_parameters', array(
			'manufacturer'=>BabelFish::Get($explicit_manufacturer),
			'model'=>BabelFish::Get($explicit_model)
		));

		$this->assign('PREPEND_CONTENT', $this->render_partial('tabheader', compact('sheet')));

		$this->assign('plugins', array(
			'DeviceHistory',
			'CompareDevices',
			'DeviceSimilarity',
			'BestDevices',
			'DeviceOwner',
		));
	}
?>
<!--
<div id="data_sheets_global_box">
	<a href="#" title_phrase="DATASHEET_PRINT"><img src="<?=Assets::Image('big/big_print.png')?>" alt_phrase="DATASHEET_PRINT"/></a><br />
	<a href="#" title_phrase="DATASHEET_PDF"><img src="<?=Assets::Image('big/big_pdf.png')?>" alt_phrase="DATASHEET_PDF" /></a><br />
	<a href="#" title_phrase="DATASHEET_MAIL"><img src="<?=Assets::Image('big/big_mail.png')?>" alt_phrase="DATASHEET_MAIL" /></a><br />

</div>
-->

<div id="data_sheets" style="overflow:auto">
<?php $first_written = false; ?>
<?php foreach($sheet as $page => $pagedata):?>
	<?php if(strtoupper($page) != 'COMPLETE'):?>
		<?php if (!$first_written): ?>
		<div id="<?=str_replace(' ', '_', $page)?>" class="loaded">
			<?=Controller::Render('datasheets', 'Datasheets_Compare', 'datasheet', $page)?>
		<?php $first_written = true; ?>
		<?php else: ?>
		<div id="<?=str_replace(' ', '_', $page)?>">
		<?php endif; ?>
		</div>
	<?php elseif(Youser::Is('god', 'root', 'administrator')):?>
		<?php if (!$first_written): ?>
			<div id="<?=str_replace(' ', '_', $page)?>" class="loaded">
				<?=Controller::Render('datasheets', 'datasheets', 'phonesheet', $device_id, $page)?>
			<?php $first_written = true; ?>
		<?php else: ?>
			<div id="<?=str_replace(' ', '_', $page)?>">
		<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endforeach;?>
</div>