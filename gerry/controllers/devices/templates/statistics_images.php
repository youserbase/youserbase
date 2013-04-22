<table style="width: 100%;">
<?php $i=0; foreach ($statistics as $device_id=>$count): ?>
	<tr class="r<?=$i?> a<?=$i++%2?>">
		<td class="r0 a0"><device id="<?=$device_id?>"/></td>
		<td class="r1 a1"><?=number_format($count, 0, ',', '.')?></td>
	</tr>
<?php endforeach; ?>
</table>