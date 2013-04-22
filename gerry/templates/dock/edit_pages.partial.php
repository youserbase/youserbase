<ul class="indicators">
	<li>
		<a href="<?=FrontController::GetLink('Administration', 'Dock', 'Settings', array('site_id'=>FrontController::GetLocationHash(), 'title'=>$PAGETITLE))?>" class="lightbox">
			<img src="<?=Assets::Image('famfamfam/cog.png')?>" alt_phrase="NAVIGATION_DOCK_TABS" title_phrase="NAVIGATION_DOCK_TABS"/>
		</a>
	</li>
	<li>
		<a href="<?=FrontController::GetLink('Administration', 'Dock', 'Plugins', array('site_id'=>FrontController::GetLocationHash()))?>" class="lightbox">
			<span class="dock-sprite plugin icon tipsify south" title_phrase="NAVIGATION_DOCK_PLUGINS">&nbsp;</span>
		</a>
		<a href="<?=FrontController::GetLink('Administration', 'Dock', 'Plugins', array('site_id'=>FrontController::GetLocationHash(), 'scope'=>'before'))?>" class="lightbox">
			<img src="<?=Assets::Image('famfamfam/book_previous.png')?>" class="tipsify south" title_phrase="CONTENT_PLUGINS_BEFORE" />
		</a>
		<a href="<?=FrontController::GetLink('Administration', 'Dock', 'Plugins', array('site_id'=>FrontController::GetLocationHash(), 'scope'=>'after'))?>" class="lightbox">
			<img src="<?=Assets::Image('famfamfam/book_next.png')?>" class="tipsify south" title_phrase="CONTENT_PLUGINS_AFTER" />
		</a>
	</li>
	<li>
		<a href="<?=FrontController::GetLink('Administration', 'Dock', 'Tabs', array('site_id'=>FrontController::GetLocationHash()))?>" class="lightbox">
			<span class="dock-sprite tab-edit icon tipsify south" title_phrase="NAVIGATION_DOCK_TABS">&nbsp;</span>
		</a>
	</li>
</ul>