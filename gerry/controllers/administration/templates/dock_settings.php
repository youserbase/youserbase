<form action="<?=FrontController::GetLink()?>" method="post" class="yform columnar ajax">
	<fieldset>
		<div class="type-text">
			<label for="dc_title"><phrase id="PAGE_TITLE"/></label>
			<phrase id="<?=$_REQUEST['title']?>"/>
		</div>
		<div class="type-text">
			<label for="dc_meta_description"><phrase id="META_DESCRIPTION"/>:</label>
			<input id="dc_meta_description" type="text" name="meta_description" value="<?=Sitemap::GetMetaDescription($site_id)?>" maxlength="64"/>

			<div class="type-check" id="dc_meta_description_phrase">
			<?php if (Sitemap::GetMetaDescription($site_id)): ?>
				<phrase id="<?=Sitemap::GetMetaDescription($site_id)?>"/>
			<?php endif; ?>
			</div>
		</div>

		<div class="type-text">
			<label for="dc_meta_keywords"><phrase id="META_KEYWORDS"/>:</label>
			<input id="dc_meta_keywords" type="text" name="meta_keywords" value="<?=Sitemap::GetMetaKeywords($site_id)?>" maxlength="64"/>

			<div class="type-check" id="dc_meta_keywords_phrase">
			<?php if (Sitemap::GetMetaKeywords($site_id)): ?>
				<phrase id="<?=Sitemap::GetMetaKeywords($site_id)?>"/>
			<?php endif; ?>
			</div>
		</div>

		<div class="type-select">
			<label for="dc_parent_site"><phrase id="NAVIGATION_PARENT_SITE"/>:</label>
			<?=$this->render_partial('site_select', array('id'=>'dc_parent_site', 'selected'=>Sitemap::GetParentSite($site_id), 'empty'=>'NO_PARENT_ITEM'))?>
		</div>

		<div class="type-select">
			<label for="dc_navigation"><phrase id="NAVIGATION"/>:</label>
			<select id="dc_navigation" name="navigation">
				<option value="-1">- <phrase id="NONE" quiet="true"/> -</option>
			<?php foreach (Navigation::GetNavigationIds() as $n_id): ?>
				<option <?=Sitemap::GetNavigation($site_id)==$n_id?'selected="selected"':''?>><?=$n_id?></option>
			<?php endforeach; ?>
			</select>
		</div>

		<input type="hidden" name="site_id" value="<?=$site_id?>"/>
	</fieldset>
	<div class="type-button">
		<button type="submit"><span><phrase id="SAVE"/></span></button>
		<button class="cancel lightbox close"><span><phrase id="CANCEL"/></span></button>
	</div>
</form>