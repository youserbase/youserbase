<div>
<table>
<tr>
<?php $count = 0;?>
	<?php foreach ($history as $id => $device_data):?>
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
				<?if (isset($device_id)):?>
					<form class="ajax reload_tab" action="<?=FrontController::GetLink('devicesurfhistory', 'addDeviceToCompare')?>" method="POST">
						<dl>
							<dt>
								<input type="hidden" name="device_id" value="<?=$device_id?>"/>
								<input type="hidden" name="add_id" value="<?=$id?>"/>
							</dt>
							<dd>
								<input type="submit" value_phrase="ADD_TO_COMPARE">
							</dd>
						</dl>
					</form>
				<?php endif;?>
				<?php if(isset($device_id) && $id != $device_id):?>
					<form class="" action="<?=FrontController::GetLink('devicesurfhistory', 'removeDeviceFromHistory')?>" method="POST">
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
			<?php $count = $count+1;?>
		<?php endif;?>
	<?php endforeach;?>
</tr>
</table>
</div>
