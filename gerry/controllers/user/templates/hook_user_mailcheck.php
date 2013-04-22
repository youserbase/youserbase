<?php if ($mail_count>0): ?>
<img src="<?=Assets::Image('famfamfam/email.png')?>" alt=""/>
<?php else: ?>
<img src="<?=Assets::Image('famfamfam/email_open.png')?>" alt=""/>
<?php endif; ?>
<a href="<?=FrontController::GetLink('User', 'Messages', 'Index')?>">
	<phrase id="ALERT_NEWMAIL" mail_count="<?=numberformat($mail_count)?>"/>
</a>
<hr/>
