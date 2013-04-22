<?php
	$this->assign('tabs', array(
		'Inbox'=>'<phrase id="INBOX" count="'.number_format($inbound_count,0,',','.').'">Eingang (#{count})</phrase>',
		'Outbox'=>'<phrase id="OUTBOX" count="'.number_format($outbound_count,0,',','.').'">Ausgang (#{count})</phrase>'
	));
?>