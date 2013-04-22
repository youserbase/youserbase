<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$phrases_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('phrases', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<form action="<?=FrontController::GetLink()?>" method="post" class="ajax target:tab">
<table style="width: 100%;" cellspacing="0" cellpadding="0" class="zebra">
	<colgroup>
		<col width="25%"/>
		<col/>
		<col width="32px"/>
	</colgroup>
	<thead>
		<tr>
			<th><phrase id="PHRASE_ID"/></th>
			<th><phrase id="CONTENT"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php $i=0; foreach ($phrases as $phrase_id=>$phrase): ?>
		<tr class="r<?=$i?> a<?=$i++%2?>">
			<td>
				<input type="text" name="phrases[<?=$phrase_id?>][id]" value="<?=$phrase_id?>" style="width: 95%;"/>
			</td>
			<td>
				<textarea name="phrases[<?=$phrase_id?>][phrase]" class="autogrow" style="width: 97%;"><?=$phrase?></textarea>
			</td>
			<td style="text-align: right;">
				<a href="<?=FrontController::GetLink(array('delete'=>$phrase_id, 'language'=>$current_language, 'page'=>$current_page))?>" class="confirm ajax target:tab" title_phrase="DELETE">
					<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" style="text-align: right;">
				<input type="submit" value_phrase="CHANGE"/>
			</td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="language" value="<?=$current_language?>"/>
</form>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$phrases_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('phrases', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>