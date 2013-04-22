<table style="width: 100%" cellspacing="0" cellpadding="2" class="sortable">
	<thead>
		<tr>
			<th><phrase id="YOUSER_ID"/></th>
			<th><phrase id="NICKNAME"/></th>
			<th><phrase id="LAST_ACTION"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php $i=0; foreach ($yousers as $youser_id=>$last_action): ?>
		<tr class="r<?=$i?> a<?=$i++%2?>">
			<td><?=$youser_id?></td>
			<td><?=Youser::Get($youser_id)->nickname?></td>
			<td><?=dateformat($last_action)?></td>
			<td>
				<a href="<?=FrontController::GetLink('Download', array('youser_id'=>$youser_id))?>" title_phrase="DOWNLOAD_AS_CSV">
					<img src="<?=Assets::Image('famfamfam/disk.png')?>" alt_phrase="DOWNLOAD_AS_CSV"/>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>