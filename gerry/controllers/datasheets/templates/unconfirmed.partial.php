<div class="unconfirmed_devices">
<?php if($device_count == 0):?>
	<phrase id="NO_UNCONFIRMED_DEVICES"/>
<?php else:?>
	<?=$device_count?> <?=($device_count == 1)?'<phrase id="UNCONFIRMED_DEVICE"/>':'<phrase id="UNCONFIRMED_DEVICES"/>';?>
		<div class="pagination">
			<?php if($skip_device >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => -10))?>">
			<?php endif;?>
			<phrase id="FIRST"/>
			<?php if($skip_device >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_device >= 10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $skip_device-20))?>">
			<?php endif;?>
			<phrase id="PREVIOUS"/>
			<?php if($skip_device >= 10):?>
				</a>
			<?php endif;?>
			<?php if($skip_device <= $device_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $skip_device))?>">
			<?php endif?>
			<phrase id="NEXT"/>
			<?php if($skip_device <= $device_count-10):?>
				</a>
			<?php endif;?>
			<?php if($skip_device != $device_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $device_count-20))?>">
			<?php endif?>
			<phrase id="LAST"/>
			<?php if($skip_device != $device_count-10):?>
				</a>
			<?php endif?>
		</div>
		<form method="POST" action="<?=FrontController::GetLink('update_device')?>">
			<table>
				<thead>
					<tr>
						<th>
						</th>
						<th>
							<phrase id="CONFIRM"/>
						</th>
						<th>
							<phrase id="DELETE"/>
						</th>
						<th>
							<phrase id="DEVICE"/>
						</th>
						<th>
							<phrase id="YOUSER"/>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($device_ids as $count => $device_id):?>				
						<tr>
							<td>
								<?=$count+$skip_device+1?>
							</td>
							<td>
								<input type="checkbox" name="confirm_id[]" value="<?=$device_id['device_id']?>"/>
							</td>
							<td>
								<input type="checkbox" name="delete_id[]" value="<?=$device_id['device_id']?>"/>
							</td>
							<td>
								<device id="<?=$device_id['device_id']?>"/>
							</td>
							<td>
								<youser id="<?=$device_id['youser_id']?>"/>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<button>
				<span>
					<phrase id="UPDATE_MARKED"/>
				</span>
			</button>
		</form>
		<div class="pagination">
			<?php if($skip_device >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => -10))?>">
			<?php endif;?>
					<phrase id="FIRST"/>
			<?php if($skip_device >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_device >= 10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $skip_device-20))?>">
			<?php endif;?>
				<phrase id="PREVIOUS"/>
			<?php if($skip_device >= 10):?>
				</a>
			<?php endif;?>
			<?php if($skip_device <= $device_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $skip_device))?>">
			<?php endif?>
				<phrase id="NEXT"/>
			<?php if($skip_device <= $device_count-10):?>
				</a>
			<?php endif;?>
			<?php if($skip_device != $device_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_device' => $device_count-20))?>">
			<?php endif?>
				<phrase id="LAST"/>
			<?php if($skip_device != $device_count-10):?>
				</a>
			<?php endif?>
		</div>
	<?php endif;?>
</div>
