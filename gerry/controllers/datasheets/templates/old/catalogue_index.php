<div class="rbox">
	<div class="rbox_options"><?=$this->render_partial('catalogue.views', array('__link_parameters'=>compact('manufacturer_id')))?></div>
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
						<th>Name</th>
						<th>Anzahl der Geräte</th>
<!--
						<th>&nbsp;</th>
-->
					</tr>
				</thead>
				<tbody>
				<?php $i=0; foreach ($manufacturers as $manufacturer_id=>$manufacturer): ?>
				<?php if (!isset($manufacturer_count[$manufacturer_id])) continue; ?>
					<tr class="r<?=$i?> a<?=$i++%2?>">
						<td class="bwhite"><manufacturer id="<?=$manufacturer_id?>" image="tiny"/></td>
						<td><manufacturer id="<?=$manufacturer_id?>"/></td>
						<td style="text-align: center;"><?=number_format($manufacturer_count[$manufacturer_id], 0, Locale::Get('decimal_separator'), Locale::Get('thousands_separator'))?></td>
<!--
						<td><?=print_r($manufacturer, true)?></td>
-->
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<ul class="catalogue manufacturers view_<?=Session::Get('catalogue', 'view')?>">
			<?php $i=0; foreach ($manufacturers as $manufacturer_id=>$manufacturer): ?>
			<?php if (!isset($manufacturer_count[$manufacturer_id])) continue; ?>
				<li class="r<?=$i++?> <?=$manufacturer_names[$manufacturer_id]?>">
					<manufacturer id="<?=$manufacturer_id?>" image="small"/>
				</li>
			<?php endforeach;?>
			</ul>
			<br style="clear: left;"/>
		<?php endif; ?>
		</div>
	</div>
</div>