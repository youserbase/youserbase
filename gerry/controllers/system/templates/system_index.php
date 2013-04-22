<?php if (!Youser::Id()): ?>
<div id="intro_youserbase">
    <div class="kernel">
        <div>
        	<div class="wrap">
				<page id="INTRO_YOUSERBASE_TEASER" rbox="false"/>
			</div>
        </div>
        <div style="display: none;">
			<img class="intro_picto" src="<?=Assets::Image('_t/youserintro/youserintro_know.gif')?>" alt="You know" />
        	<div class="wrap">
        		<page id="INTRO_KNOW_TEASER" rbox="false"/>
			</div>
        </div>
        <div style="display: none;">
			<img class="intro_picto" src="<?=Assets::Image('_t/youserintro/youserintro_share.gif')?>" alt="You know" />
        	<div class="wrap">
				<page id="INTRO_SHARE_TEASER" rbox="false"/>
			</div>
        </div>
        <div style="display: none;">
			<img class="intro_picto" src="<?=Assets::Image('_t/youserintro/youserintro_choose.gif')?>" alt="You know" />
			<div class="wrap">
	        	<page id="INTRO_CHOOSE_TEASER" rbox="false"/>
			</div>
        </div>
    </div>
    <div class="controls">
        <span><a href="<?=FrontController::GetLink(array('#'=>'know'))?>" onclick="this.blur(); return false;"><img src="<?=Assets::Image('_t/know.gif')?>" alt="You know" /></a></span>
        <span><a href="<?=FrontController::GetLink(array('#'=>'share'))?>" onclick="this.blur(); return false;"><img src="<?=Assets::Image('_t/share.gif')?>" alt="You share" /></a></span>
        <span><a href="<?=FrontController::GetLink(array('#'=>'choose'))?>" onclick="this.blur(); return false;"><img src="<?=Assets::Image('_t/choose.gif')?>" alt="You choose" /></a></span>
    </div>
</div>
<?php endif; ?>
