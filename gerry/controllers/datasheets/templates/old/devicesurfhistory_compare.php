<div class="submit_rating">
</div>
<?php if (isset($compare)):?>
<table>
<tr>
<?php $count = 0;?>
<?php foreach ($compare as $id => $device_data):?>
	<?php if($count <= 6):?>
		<td class="devices_history">
			<dl>
				<dt>
					<a href="<?=FrontController::GetLink('datasheets', 'page', array('device_id' => $id))?>">
						<phrase id="<?=$device_data['manufacturer_name']?>"/> <phrase id="<?=$device_data['device_name']?>"/>
					</a>
				</dt>
				<dd>
					<a href="<?=FrontController::GetLink('datasheets', 'page', array('device_id' => $id))?>">
						<img src="<?=str_replace(array('jpeg', 'small', 'thumb'), array('png', 'medium', 'medium'), $device_data['device_picture'])?>" alt="device_picture"/>
					</a>
				</dd>
				<dt>
					<label for="device_rating"><phrase id="DEVICE_RATING"/></label>
				</dt>
				<dd>
					<div class="ratingstar">
						<div style="width:<?=$device_data['device_rating']?>%">
						</div>
					</div>
				</dd>
			</dl>
			<?php if (isset($device_id)):?>
				<form class="ajax reload_tab" action="<?=FrontController::GetLink('devicesurfhistory', 'removeDeviceFromCompare')?>" method="POST">
					<dl>
						<dt>
							<input type="hidden" name="device_id" value="<?=$device_id?>"/>
							<input type="hidden" name="remove_id" value="<?=$id?>"/>
						</dt>
						<dd>
							<input type="submit" value_phrase="DELETE">
						</dd>
					</dl>
				</form>
			<?php endif;?>
		</td>
	<?php endif;?>
<?php endforeach;?>
</tr>
<?php if(count($compare) > 1):?>
<tr>
	<td colspan="<?=count($compare)?>;">
		<form action="<?=FrontController::GetLink('sheetcomparison', 'compare')?>" method="POST">
		<?php foreach ($compare as $id => $device_data):?>
			<input type="hidden" name="<?=$device_data['device_name']?>" value="<?=$id?>"/>
		<?php endforeach;?>		
			<input type="submit" value_phrase="compare"/>
		</form>
	</td>
</tr>
<?php endif;?>
</table>
<?php endif;?>