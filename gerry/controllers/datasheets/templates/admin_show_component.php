<?php if(isset($table) && is_array($table)):?>
	<table>
		<thead>
			<tr>
				<th colspan="2">
					<phrase id="<?=strtoupper($table_name)?>"/>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($table as $component):?>
				<?php foreach ($component as $key => $value):?>
					<?php if(isset($value['input']['text'])):?>
						<tr>
							<td>
								<phrase id="<?=strtoupper($key)?>"/>
							</td>
							<td>
								<?=$value['input']['text']?>
							</td>
						</tr>
					<?php endif;?>
				<?php endforeach;?>
			<?php endforeach;?>
		</tbody>
	</table>
	<a href="<?=FrontController::GetLink('confirm', array('device_id' => $device_id, 'table_name' => $table_name, 'youser_id' => $youser_id, 'component_id' => $component_id))?>"><phrase id="CONFIRM"/></a>
<?php else:?>
	Kiene Daten zu <phrase id="<?strtoupper($table_name)?>"/> vorhaden.
<?php endif;?>
