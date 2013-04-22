<?php
	$this->assign('page_title_parameters', array(
		'manufacturer'=>BabelFish::Get($manufacturer['name'])
	));
	$this->assign('TITLE_HASH', FrontController::GetLocationHash('Manufacturer'));
?>

<div class="rbox">
	<div class="rbox_options"><?=$this->render_partial('catalogue.views')?></div>
	<div class="rbox_k">
		<h3>
			<phrase id="MANUFACTURERS"/>
			&gt;
			<manufacturer id="<?=$manufacturer['id']?>" link="false"/>
		</h3>
		<div class="content">
			<form action="<?=FrontController::GetLink('datasheets', 'Datasheets_Compare', 'Index')?>" method="post">
			<?php if (Session::Get('catalogue', 'view')=='list'): ?>

			<table border="1" class="sortable full">
				<colgroup>
					<col width="5%"/>
					<col width="10%"/>
					<col width="45%"/>
					<col width="10%"/>
					<col width="10%"/>
					<col width="30%"/>
				</colgroup>
				<thead>
					<tr>
						<th class="{sorter: false}">&nbsp;</th>
						<th class="{sorter: false}">&nbsp;</th>
						<th><phrase id="NAME"/></th>
						<th><phrase id="RANK"/></th>
						<th><phrase id="RATING"/></th>
						<th class="{sorter: 'timestamp'}"><phrase id="TIMESTAMP_UPDATED"/></th>
					</tr>
				</thead>
				<tbody>
				<?php $i=0; foreach($devices as $device_id=>$data): ?>
					<tr class="r<?=$i?> a<?=$i++%2?>">
						<td class="r0 a0" style="text-align: center;">
							<input type="checkbox" name="compare[]" value="<?=$device_id?>"/>
						</td>
						<td class="r1 a1 bwhite" style="text-align: center;">
							<device id="<?=$device_id?>" type="avatar" manufacturer="false"/>
						</td>
						<td class="r2 a0">
							<device id="<?=$device_id?>" manufacturer="false"/>
						</td>
						<td class="r3 a1">
							<?=numberformat($data['rank'])?>
						</td>
						<td class="r4 a0">
						<?php if ($data['rating']===null): ?>
							<?=number_format(0)?>
						<?php else: ?>
							<?=numberformat($data['rating'], 2)?>
						<?php endif; ?>
						</td>
						<td class="r5 a1">
						<?php if (!empty($data['timestamp'])): ?>
							<?=dateformat($data['timestamp'])?>
						<?php else: ?>
						<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6">
							<button type="submit"> <span> <phrase id="COMPARE"/> </span> </button>
						</td>
					</tr>
				</tfoot>
			</table>
			<?php else: ?>
			<ul class="catalogue products view_<?=Session::Get('catalogue', 'view')?>">
			<?php $i=0; foreach($devices as $device_id=>$data): ?>
				<li class="r<?=$i?> a<?=$i++%2?>">
					<device id="<?=$device_id?>" type="avatar" manufacturer="false"/>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			</form>
		</div>
	</div>
</div>
