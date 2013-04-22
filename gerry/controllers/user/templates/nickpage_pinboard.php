<div>
	<h2 class="blue"><phrase id="PINBOARD"/></h2>
	<div class="subheader">
		<a class="lightbox" href="<?=FrontController::GetLink('AddEntry', array('youser_id'=>$youser_id))?>">
			<img src="<?=Assets::Image('famfamfam/note_add.png')?>" alt=""/>
			<span><phrase id="ADD_PINBOARD_ENTRY"/></span>
		</a>
		<a class="ajax target:#pinboard" href="<?=FrontController::GetLink('Pinboard', array('page'=>$current_page, 'youser_id'=>$youser_id))?>">
			<img src="<?=Assets::Image('famfamfam/note.png')?>" alt=""/>
		</a>
	</div>
<?php if (empty($pinboard)): ?>
	<div class="aligncenter">
		<phrase id="NO_ENTRIES"/>
	</div>
<?php else: ?>
	<table style="width: 100%;" cellspacing="0" cellpadding="2" class="multiple_bodies pinboard" rel="<?=FrontController::GetLink('Pinboard')?>">
		<colgroup>
			<col width="80px"/>
			<col/>
		</colgroup>
	<?php foreach ($pinboard as $entry): ?>
		<tbody>
			<tr>
				<td rowspan="2">
					<youser id="<?=$entry['sender_id']?>" image="small"/>
				</td>
				<td class="header">
				<?php if ($youser_id==Youser::Id() or $entry['sender_id']==Youser::Id()): ?>
					<span style="float: right;">
						<a class="confirm ajax target:#pinboard" href="<?=FrontController::GetLink('Pinboard', array('page'=>$current_page, 'youser_id'=>$youser_id, 'delete'=>$entry['message_id']))?>" title_phrase="DELETE_NICKPAGE_ENTRY">
							<img src="<?=Assets::Image('famfamfam/note_delete.png')?>" alt=""/>
						</a>
					</span>
				<?php endif; ?>
					<youser id="<?=$entry['sender_id']?>"/><br/>
					<?=date('d.m.Y H:i', $entry['timestamp'])?>
				</td>
			</tr>
			<tr>
				<td>
					<?=BoxBoy::Prepare($entry['message'])?>
				</td>
			</tr>
		</tbody>
	<?php endforeach; ?>
	<?php if ($pinboard_count>Config::Get('pinboard:pagination_count')): ?>
		<tfoot>
			<tr>
				<td colspan="2">
					<pagination href="<?=FrontController::GetLink('Pinboard', array('page'=>''))?>" total="<?=$pinboard_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('pinboard', 'pagination_count')?>" style="float: right;" class="ajax"/>
				</td>
			</tr>
		</tfoot>
	<?php endif; ?>
	</table>
<?php endif; ?>
</div>

<script type="text/javascript">
//<![CDATA[
$('#pinboard tfoot div.pagination.ajax a').click(function(event) {
	$('#pinboard').load($(this).attr('href'));
	return false;
});
//]]>
</script>