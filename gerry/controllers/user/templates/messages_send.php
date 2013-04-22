<form id="message_send" action="<?=FrontController::GetLink()?>" method="post" class="validate <?=$VIA_AJAX?'ajax small':''?>">
<dl>
	<dt><phrase id="RECIPIENT"/>:</dt>
	<dd><youser id="<?=$to?>"/></dd>
	<dt><label for="subject"><phrase id="SUBJECT"/></label>:</dt>
	<dd>
		<input type="text" class="required" id="subject" name="subject" style="width: 250px;" value="<?=$subject?>"/>
	</dd>
	<dt><label for="message"><phrase id="MESSAGE"/></label>:</dt>
	<dd>
		<textarea name="message" class="required autogrow" style="min-height: 100px; max-height: 300px; width: 250px;"><?=$message?></textarea>
	</dd>
	<dt>&nbsp;</dt>
	<dd>
		<button type="submit"><span> <phrase id="SENDMESSAGE"/> </span></button>
	</dd>
</dl>
<input type="hidden" name="to" value="<?=$to?>"/>
<?php if ($reply_to!==false): ?>
<input type="hidden" name="reply_to" value="<?=$reply_to?>"/>
<?php endif; ?>
</form>

<?php if ($VIA_AJAX): ?>
<script type="text/javascript">
//<![CDATA[
if ($('#outbound_count').length) {
	$('#message_send').bind('submitted', function() {
		var count = ''+(parseInt($('#outbound_count').text().replace(/\./g, ''))+1);
		var count_formatted = '';
		for (var i=count.length, j=0; i>=0; i--, j++) {
			count_formatted = count.substr(i,1)+count_formatted;
			if (j && j%3==0) {
				count_formatted = '.'+count_formatted;
			}
		}
		$('#outbound_count').text(count_formatted)
	});
}
//]]>
</script>
<?php endif; ?>