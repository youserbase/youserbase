<table>
<tr>
<?php if(isset($best_devices)):?>
	<?php if($limit > 0):?>
		<td class="scrollbutton">
			<form class="ajax reload_tab" action="<?=FrontController::GetLink('devicesimilarity', 'changeratingpos')?>" method="POST">
				<input type="hidden" name="device_id" value="<?=$device_id?>"/>
				<input type="hidden" name="forward" value="<?=$limit-1?>"/>
				<input type="image" src="http://www.youserbase.eu/willms/gerry/famfamfam/resultset_previous.png" alt="backward">
			</form>
		</td>
	<?php endif;?>
	<?php $count=1?>
	<?php foreach ($best_devices as $id => $rating):?>
		<td class="device_similar">
			<h3><phrase id="RANK"/> <?=$limit+$count?></h3>
			<dl>
				<dt>
					<a href="<?=FrontController::GetLink('datasheets', 'page', array('device_id' => $id))?>">
						<phrase id="<?=$devices[$id][$id]['manufacturer_name']?>"/> <phrase id="<?=$devices[$id][$id]['device_name']?>"/>
					</a>
				</dt>
				<dd>
					<a href="<?=FrontController::GetLink('datasheets', 'page', array('device_id' => $id))?>">
						<img src="<?=str_replace(array('thumb', 'small', 'jpeg'), array('medium', 'medium', 'png'), $devices[$id][$id]['device_picture'])?>" alt="device_picture"/>
					</a>
				</dd>
				<dt>
					<label for="device_rating"><phrase id="DEVICE_RATING"/></label>
				</dt>
				<dd>
					<div class="ratingstar">
						<div style="width:<?=$rating/5*100?>%">
						</div>
					</div>
				</dd>
			</dl>
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
		</td>
		<?$count++?>
	<?php endforeach;?>
	<?if(count($best_devices) >= 3):?>
	<td class="scrollbutton">
	<form class="ajax reload_tab" action="<?=FrontController::GetLink('devicesimilarity', 'changeratingpos')?>" method="POST">
		<input type="hidden" name="device_id" value="<?=$device_id?>"/>
		<input type="hidden" name="forward" value="<?=$limit+1?>"/>
		<input type="image" src="http://www.youserbase.eu/willms/gerry/famfamfam/resultset_next.png" alt="forward">
		</input>
	</form>
	</td>
	<?endif;?>
<?php endif;?>
</tr>
</table>