<div class="ratingbox ratingbox_<?=$dimension ?> <?=isset($compare)?'disable':''?> stored:device_id:<?=$device_id?> stored:tab:<?=$tab?>  stored:table:<?=$table?> stored:feature:<?=$feature?> stored:sheet_tab:<?=$sheet_tab?>">
	<?php if (empty($table)): ?>
    <div class="header">
        <phrase id="<?=strtoupper($name)?>"/>
    </div>
    <?php endif;?>
	<div class="score">
	<?=$rating == 10?numberformat($rating):numberformat($rating, 1, '.')?>
	</div>
    <div class="ratingbox_canvas">
        <div class="rating_average">
            <div class="index" style="width: <?=$dimension=="big"?$rating*10+50:$rating*5+26 ?>px"></div>
        </div>
        <div class="rating_user">
            <div class="index" style="width: 0;"></div>
        </div>
    </div>
    <div class="indicator">
        <span style="display: none"></span>
    </div>
	<div class="ajax_indicator"></div>
</div>