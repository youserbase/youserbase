<div id="rotate_dock" style="display: none;">
	<span class="dock-sprite arrow-refresh-small icon">&nbsp;</span>
</div>
<div id="dropbox">
	<div id="dropbox_content">
		<form action="<?=FrontController::GetLink('datasheets', 'Compare', 'Index')?>" method="post">
			<fieldset>
				<?=Controller::Render('Dock', 'Dock_Dropbox', 'Get')?>
				<div id="dropbox_options">
					<button type="submit" name="action" value="compare" <?=Dropbox::GetCount()<2?'disabled="disabled"':''?>> <span><phrase id="COMPARE"/></span> </button>
					<button type="submit" name="action" value="remove" class="cancel"> <span><phrase id="REMOVE_FROM_DROPBOX"/></span> </button>
				</div>
				<input type="hidden" name="return_to" value="<?=FrontController::GetURL()?>"/>
			</fieldset>
		</form>
	</div>
	<phrase id="DEVICES_IN_DROPBOX"/>:
	<span id="dropbox_count">
		<?=Dropbox::GetCount()?>
	</span>
</div>
<div id="view_container">
	<div class="view youser">
		<ul class="indicators">
		<?php if (Youser::Id()): ?>
			<?=$this->render_partial('dock/mail')?>
			<?=$this->render_partial('dock/activities')?>
			<?=$this->render_partial('dock/credits')?>
			<?=$this->render_partial('dock/babelfish')?>
		<?php endif; ?>
			<?=$this->render_partial('dock/youser_online')?>
		</ul>
	</div>
<?php if (Youser::May('edit_pages')): ?>
	<div class="view root_edit_pages">
		<?=$this->render_partial('dock/edit_pages', array('PAGETITLE'=>$PAGETITLE))?>
	</div>
<?php endif; ?>
<?php if (Youser::Is('root,god')): ?>
	<div class="view root_debug">
		<a href="<?=FrontController::GetLink(array('XDEBUG_PROFILE'=>1))?>">Profile</a>
		<a href="http://dev.youserbase.org/webgrind/" class="external">WebGrind</a>
		|
		<form id="debug_toggle" action="<?=FrontController::GetLink('Debug', 'Debug', 'Toggle')?>" method="post" class="ajax">
			<fieldset>
				<input type="checkbox" id="debug_<?=$debug_id=md5(uniqid('debug', true))?>" <?=Session::Get('debugmode')?'checked="checked"':''?> onchange="$(this).parent().submit();"/>
				<label for="debug_<?=$debug_id?>">Debug</label>
			</fieldset>
		</form>
		|
		<form action="<?=FrontController::GetLink('Debug', 'Debug', 'ToggleHarvester')?>" method="post" class="ajax">
			<fieldset>
				<input type="checkbox" id="debug_<?=$debug_id=md5(uniqid('debug', true))?>" <?=Config::Get('system', 'harvest_mode')?'checked="checked"':''?> onchange="$(this).parent().submit();"/>
				<label for="debug_<?=$debug_id?>">Harvester</label>
			</fieldset>
		</form>
	</div>
<?php endif; ?>
</div>
