<?php
	$this->assign('optional_headers', '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.FrontController::GetLink('Export', 'Comments', array('type'=>'rss', 'device_id'=>$device_id)).'" />');

	$this->assign('page_title_parameters', array(
		'manufacturer'=>BabelFish::Get($explicit_manufacturer),
		'model'=>BabelFish::Get($explicit_model)
	));
	$this->assign('PREPEND_CONTENT', $this->render_partial('tabheader', compact('sheet', 'device_id', 'numbers', 'tab')));
?>
<div id="data_sheets">
	<?=Controller::Render('datasheets', 'datasheets', 'phonesheet', $device_id, $tab)?>
</div>
