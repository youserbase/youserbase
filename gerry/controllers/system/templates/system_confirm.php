<h1><phrase id="LABEL_CONFIRMATION_SUCCESSFUL"/></h1>
<?php if (empty($message)): ?>
<p>
	<phrase id="CONFIRMATION_SUCCESSFUL"/>
</p>
<?php else: ?>
<p>
	<?=$message?>
</p>
<?php endif; ?>