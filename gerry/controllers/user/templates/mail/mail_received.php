<phrase id="MAIL_GREETING" name="<?=$nickname?>" quiet="true" />

<phrase id="MAIL_RECEIVED_FROM" sender="<?=$sender?>" quiet="true" />

<?php if (!empty($message)): ?>
------------------------------------------------------------
<phrase id="SUBJECT" quiet="true" />: <?=$subject?>

------------------------------------------------------------

<?=$message?>


------------------------------------------------------------
<?php endif; ?>

<phrase id="MAIL_GOODBYE" quiet="true" />