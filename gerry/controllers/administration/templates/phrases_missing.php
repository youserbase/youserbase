<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$missing_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('phrases', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<form action="<?=FrontController::GetLink()?>" method="post" class="ajax target:reload">
<table style="width: 100%;" cellspacing="0" cellpadding="0" class="zebra">
	<colgroup>
		<col width="25%"/>
		<col/>
	</colgroup>
	<thead>
		<tr>
			<td><phrase id="PHRASE_ID"/></td>
			<td><phrase id="CONTENT"/></td>
		</tr>
	</thead>
	<tbody>
	<?php $i=0; foreach ($missing_phrases as $phrase_id): ?>
		<tr class="r<?=$i?> a<?=$i++%2?>">
			<td>
				<?=$phrase_id?>
			</td>
			<td>
				<textarea name="phrases[<?=$phrase_id?>]" class="autogrow" style="width: 97%;"></textarea>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2" style="text-align: right;">
				<input type="submit" value_phrase="ADD"/>
			</td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="language" value="<?=$current_language?>"/>
</form>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$missing_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('phrases', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>