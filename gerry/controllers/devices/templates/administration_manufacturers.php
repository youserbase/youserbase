<table style="width: 100%;" cellpadding="2" cellspacing="0">
	<thead>
		<tr>
			<th><phrase id="NAME"/></th>
			<th><phrase id="COUNT"/></th>
			<th><phrase id="WEBSITE"/></th>
			<th><phrase id="COUNTRY"/></th>
			<th><phrase id="LOGO"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php $i=0; foreach ($manufacturers as $manufacturer_id => $manufacturer): ?>
		<tr class="r<?=$i?> a<?=$i++%2?>">
			<td>
				<phrase id="<?=$manufacturer['manufacturer_name']?>"/>
			</td>
			<td>
				<?=numberformat(isset($manufacturer_count[$manufacturer_id])?$manufacturer_count[$manufacturer_id]:0)?>
			</td>
			<td>
			<?php if (!empty($manufacturer['manufacturer_website'])): ?>
				<a href="<?=$manufacturer['manufacturer_website']?>">
					<?=$manufacturer['manufacturer_website']?>
				</a>
			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			</td>
			<td style="text-align: center;">
				<img src="<?=Assets::Image('flags/%s.png', strtolower($manufacturer['country_id']))?>" alt="<?=$manufacturer['country_id']?>"/>
			</td>
			<td style="text-align: center;">
			<?php if (count(glob(ASSETS_IMAGE_DIR.'/manufacturers/'.strtolower(preg_replace('/[^[:alnum:]]/', '', str_replace(array('MANU_','_'), '', $manufacturer['manufacturer_name']))).'_logo.original.*'))): ?>
				<img src="<?=Assets::Image('famfamfam/accept.png')?>" alt="yes"/>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/cancel.png')?>" alt="no"/>
			<?php endif; ?>
			</td>
			<td style="text-align: right;">
				<a href="<?=FrontController::GetLink('Manufacturer_Edit', compact('manufacturer_id'))?>" class="lightbox">
					<img src="<?=Assets::Image('famfamfam/pencil.png')?>" alt_phrase="EDIT"/>
				</a>
				<a href="<?=FrontController::GetLink('Manufacturer_Delete', compact('manufacturer_id'))?>" class="confirm" title="DELETE_MANUFACTURER">
					<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>