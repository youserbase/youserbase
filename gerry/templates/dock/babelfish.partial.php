<?php if (Youser::May('translate')): ?>
<li>
	<!--BabelFish:UntranslatedCount-->
	<a href="<?=FrontController::GetLink('System', 'Babelfish', 'Translate', array('nonce'=>Helper::GetNonce()))?>" class="lightbox" title_phrase="DOCK_TRANSLATE">
		<span class="dock-sprite comment-edit icon" title_phrase="DOCK_TRANSLATE">&nbsp;</span>
	</a>
	<!--BabelFish:TranslatedCount-->
</li>
<?php endif; ?>
