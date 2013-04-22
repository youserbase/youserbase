<page id="404"/>
<?php if (Youser::Is('god')): ?>
<pre>
<?php var_dump(FrontController::GetLocation()); ?>
<?php var_dump($_REQUEST); ?>
<?php var_dump($_GET); ?>
<?php var_dump($_SERVER); ?>
</pre>
<?php endif; ?>