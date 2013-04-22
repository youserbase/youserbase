<?php if (empty($users_online)): ?>
<em><phrase id="NOONE"/></em>
<?php else: ?>
<ul>
<?php foreach ($users_online as $user): ?>
	<li><youser id="<?=$user?>"/></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
