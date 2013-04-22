<div class="rbox">
	<h3><phrase id="<?=strtoupper($table)?>"/> <a class="lightbox" href="<?=FrontController::GetLink('presetlist', array('table' => $table))?>">BACK</a></h3>
	<table>
		<tr>
			<?php foreach($keys as $key):?>
				<?php if($key != $table.'_id' && $key != $table.'_id_int' && $key != 'youser_id' && $key != 'timestamp'):?>
					<th>
						<phrase id="<?=strtoupper($key)?>"/>
					</th>
				<?php endif;?>
			<?php endforeach;?>
			<th>
				&nbsp;
			</th>
		</tr>
		<?php foreach ($details as $detail):?>
			<tr>
				<?php foreach($detail as $key => $value):?>
					<?php if($key != $table.'_id' && $key != $table.'_id_int' && $key != 'youser_id' && $key != 'timestamp'):?>
						<td>
							<?php if(($table == 'processor_type' || $table == 'manufacturer') && $key == 'manufacturer_id'):?>
								<?php if(isset($manufacturers[$value]['manufacturer_name'])):?>
									<phrase id="<?=$manufacturers[$value]['manufacturer_name']?>"/>
								<?php endif;?>
							<?php elseif($key == 'manufacturer_website'):?>
								<?=$value?>
							<?php elseif($key == 'country_id'):?>
								<?php if(isset($countries[$value])):?>
									<phrase id="<?=$countries[$value]?>"/>
								<?php endif;?>
							<?php elseif($key == 'continent_id'):?>
								<?php if(isset($continents[$value])):?>
									<phrase id="<?=$continents[$value]?>"/>
								<?php endif;?>
							<?php else:?>
								<phrase id="<?=$value?>"/>
							<?php endif;?>
						</td>
					<?php endif;?>
				<?php endforeach;?>
				<td>
					<img src="<?=Assets::Image('famfamfam/application_edit.png')?>" class="edit" id="<?=$detail[$table.'_id']?>" alt_phrase="EDIT" title_phrase="EDIT"/>
					<img src="<?=Assets::Image('famfamfam/cancel.png')?>" style="display:none" class="edit" id="<?=$detail[$table.'_id']?>" alt_phrase="CANCEL" title_phrase="CANCEL"/>
					<a class="lightbox parent_reload_link" href="<?=FrontController::GetLink('deletepreset', array('table' => $table, 'id' => $detail[$table.'_id'], 'detail' => $specific, 'device_id' => isset($device_id)?$device_id:null, 'tab' => isset($tab)?$tab:null))?>" class="confirm" title_phrase="DELETE_DEVICE">
						<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
					</a>
				</td>
			</tr>
		</table>
			<form class="validate parent_reload <?=$detail[$table.'_id']?>" method="post" action="<?=FrontController::GetLink('updatepreset')?>"style="display:none">
				<table>
				<tr>
					<th colspan="3">
						<phrase id="CHANGE_PRESET_TO"/>
					</th>
				</tr>
				<tr>
					<?php foreach($detail as $key => $value):?>
						<?php if($key != $table.'_id' && $key != $table.'_id_int' && $key != 'youser_id' && $key != 'timestamp'):?>
							<td>
								<?php if($table == 'processor_type' && $key == 'manufacturer_id'):?>
									<select name="manufacturer_id">
										<?php foreach ($manufacturers as $id => $manufacturer):?>
										<option value="<?=$id?>">
											<phrase id="<?=$manufacturer['manufacturer_name']?>"/>
										</option>
										<?php endforeach;?>
									</select>
								<?php elseif($key == 'country_id'):?>
									<select name="country_id">
										<?php foreach ($countries as $id => $country):?>
											<option value="<?=$id?>">
												<phrase id="<?=$country?>"/>
											</option>
										<?php endforeach;?>
									</select>
								<?php elseif($key == 'continent_id'):?>
									<select name="continent_id">
										<?php foreach ($continents as $id => $continent):?>
											<option value="<?=$id?>">
												<phrase id="<?=$continent?>"/>
											</option>
										<?php endforeach;?>
									</select>
								<?php else:?>
									<input type="text" name="<?=$key?>" value="<?=$value?>"/>
								<?php endif;?>
							</td>
						<?php else:?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>"/>
						<?php endif;?>
					<?php endforeach;?>
					<?php if(isset($device_id) && isset($tab)):?>
						<input type="hidden" name="device_id" value="<?=$device_id?>"/>
						<input type="hidden" name="tab" value="<?=$tab?>"/>
					<?php endif;?>
					<input type="hidden" name="table" value="<?=$table?>"/>
					<input type="hidden" name="detail" value="<?=$specific?>"/>
					<td>
						<button type="submit">
							<span>
								<phrase id="SAVE"/>
							</span>
						</button>
					</td>
					<td>
						<button type="submit" class="cancel">
							<span>
								<phrase id="CANCEL"/>
							</span>
						</button>
					</td>
				</tr>
			</table>
		</form>
	<?php endforeach;?>
</div>
<script>
$('.parent_reload_link').live('click', function()
{
	$('.parent_reload').ajaxSuccess(function(){
		var var_device_id = $("input[name='device_id']").val();
		var var_tab = $("input[name='tab']").val();
		$.get("<?=FrontController::GetLink('datasheets', 'datasheets', 'phonesheet')?>", {device_id: var_device_id, tab: var_tab},
		function(data)
		{
			$('div .phonesheet').replaceWith(data);
		});
	});
});

$('.edit').live('click', function(){
	var toggle_class = $(this).attr('id');
	$('.'+toggle_class).toggle();
});

</script>