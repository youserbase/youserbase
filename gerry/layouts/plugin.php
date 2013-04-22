<?php $outer_index = 0; ?>
<?php foreach ($plugins as $plugin): ?>
<?php if ($plugin['wrapper']): ?>
<div class="rbox r<?=$outer_index++?>" id="plugin_<?=strtolower($plugin['name'])?>">
<?php if ($plugin['header']): ?>

<?php if (Youser::May('edit_plugins')): ?>
	<div class="rbox_options">
	<?php if (count(PluginEngine::GetOptions($plugin['name']))): ?>
		<a href="<?=FrontController::GetLink('Administration', 'Plugins', 'Configuration', array('plugin_name'=>$plugin['name']))?>" class="lightbox">
			<img src="<?=Assets::Image('famfamfam/plugin_edit.png')?>" alt_phrase="OPTION_PLUGIN_CONFIG"/>
		</a>
	<?php else: ?>
		<img src="<?=Assets::Image('famfamfam/plugin_disabled.png')?>" alt=""/>
	<?php endif; ?>
	</div>
<?php endif; ?>

	<h3>
	<?php if (!empty($plugin['link'])): ?>
		<a href="<?=$plugin['link']?>"><phrase id="plugin_<?=$plugin['name']?>"/></a>
	<?php else: ?>
		<phrase id="plugin_<?=$plugin['name']?>"/>
	<?php endif; ?>
	</h3>
<?php endif; ?>
<?php endif; ?>
	<div class="content">
		<?=$plugin['content']?>
	</div>
<?php if ($plugin['wrapper']): ?>
</div>
<?php endif; ?>
<?php endforeach; ?>