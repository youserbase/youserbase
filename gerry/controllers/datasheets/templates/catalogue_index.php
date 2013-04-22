<div class="rbox">
	<div class="rbox_options"><?=$this->render_partial('catalogue.views')?></div>
	<div class="rbox_k">
		<h3><phrase id="MANUFACTURERS"/></h3>
		<div class="content">
		<?php if (Session::Get('catalogue', 'view')=='list'): ?>
			<table class="sortable full">
				<colgroup>
					<col width="120px"/>
					<col/>
					<col width="200px"/>
				</colgroup>
				<thead>
					<tr>
						<th class="{sorter: false}">&nbsp;</th>
						<th><phrase id="NAME"/></th>
						<th><phrase id="DEVICE_COUNT"/></th>
						<th><phrase id="RANK"/></th>
					</tr>
				</thead>
				<tbody>
				<?php $i=0; foreach ($manufacturers as $manufacturer_id=>$manufacturer): ?>
				<?php if (!$manufacturer['devices']) continue; ?>
					<tr class="r<?=$i?> a<?=$i++%2?>">
						<td class="bwhite r0"><manufacturer id="<?=$manufacturer_id?>" image="tiny"/></td>
						<td><manufacturer id="<?=$manufacturer_id?>"/></td>
						<td style="text-align: center;"><?=numberformat($manufacturer['devices'])?></td>
						<td><?=numberformat(isset($manufacturer['rank'])?$manufacturer['rank']:0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<ul class="catalogue manufacturers view_<?=Session::Get('catalogue', 'view')?>">
			<?php $i=0; foreach ($manufacturers as $manufacturer_id=>$manufacturer): ?>
			<?php if (!$manufacturer['devices']) continue; ?>
				<li class="r<?=$i++?> <?=$manufacturer['short_name']?>">
					<manufacturer id="<?=$manufacturer_id?>" image="small"/>
				</li>
			<?php endforeach;?>
			</ul>
			<br style="clear: left;"/>
		<?php endif; ?>
		</div>
	</div>
</div>