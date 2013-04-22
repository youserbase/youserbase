<form action="<?=FrontController::GetURL()?>" method="post" <?=$VIA_AJAX?'class="ajax"':''?>>
<div class="rbox">
	<?php foreach ($options as $name=>$o): ?>
	<h1><phrase id="plugin_<?=$name?>"/> <em><?=$name?></em></h1>
	<div class="rbox_k">
		<dl>
		<?php foreach ($o as $key=>$option): ?>
			<dt>
				<phrase id="PLUGINCONFIG_<?=$name?>_<?=$key?>"/>
			</dt>
			<dd>
				<?=$this->render_partial('option', array('option'=>$option, 'section'=>$name, 'key'=>$key, 'value'=>PluginEngine::GetConfig($name, $key, 'plugin')))?>
			</dd>
		<?php endforeach; ?>
		</dl>
	</div>
	<?php endforeach; ?>
	<button type="submit"><span>Speichern</span></button>
</div>
</form>
