<div class="systempage rbox">
<?php if (Youser::May('edit_pages')): ?>
	<div class="info">
		<a href="<?=FrontController::GetLink('Update', compact('page'))?>">
			<img src="<?=Assets::Image('famfamfam/page_add.png')?>" alt_phrase="PAGES_ADD"/>
		</a>
	</div>
<?php endif; ?>
	<p style="color: red; font-size: 24px;">
		<phrase id="PAGES_MISSING" page_id="<?=$page?>"/>
	</p>
	<div class="clearfix"></div>
</div>
