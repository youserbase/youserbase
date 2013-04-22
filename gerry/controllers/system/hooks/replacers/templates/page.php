<?php if (!$quiet): ?>
<div id="page_<?=strtolower($page)?>" class="systempage <?=$rbox?'rbox':''?> <?=empty($revision)?'missing':''?>">
<?php endif; ?>
<?php if (Youser::May('edit_pages')): ?>
	<div class="options">
	<?php if (empty($revision)): ?>
		<phrase id="PAGE_NOT_AVAILABLE"/>
		<a href="<?=FrontController::GetLink('System', 'Pages', 'Update', compact('page'))?>" class="update">
			<img src="<?=Assets::Image('famfamfam/page_add.png')?>" alt_phrase="PAGES_EDIT"/>
		</a>
	<?php else: ?>
		<phrase id="REVISION"/>: <?=$revision?><br/>
		<phrase id="AUTHOR"/>: <youser id="<?=$youser_id?>"/><br/>
		<?=date(Locale::Get('datetime_format'), $timestamp)?><br/>
		<a href="<?=FrontController::GetLink('System', 'Pages', 'Update', compact('page'))?>" class="update">
			<img src="<?=Assets::Image('famfamfam/page_edit.png')?>" alt_phrase="PAGES_EDIT"/>
		</a>
	<?php endif; ?>
	</div>
<?php endif; ?>
	<?=empty($contents)?'<p><phrase id="NO_CONTENTS_FOR_PAGE" page="'.$page.'"/></p>':$contents?>
<?php if (!$quiet): ?>
</div>
<?php endif; ?>
