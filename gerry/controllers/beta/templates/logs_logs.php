<pagination href="<?=FrontController::GetLink()?>" current_page="<?=$page?>" max="<?=$display_max?>" total="<?=$total?>"/>
<table style="width: 100%;" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
			<th><phrase id="TIMESTAMP"/></th>
			<th><phrase id="LOCATION"/></th>
			<th><phrase id="LANGUAGE"/></th>
		</tr>
	</thead>
	<tbody>
	<?php for($i=0; $i<$display_max; $i++): ?>
		<tr class="r<?=$i?> a<?=$i%2?>">
		<?php if (isset($data[$i])): ?>
			<td><?=dateformat($data[$i]['timestamp'])?></td>
			<td><?=$data[$i]['location']?></td>
			<td><?=$data[$i]['language']?></td>
		<?php else: ?>
			<td colspan="3">&nbsp;</td>
		<?php endif; ?>
		</tr>
	<?php endfor; ?>
	</tbody>
</table>