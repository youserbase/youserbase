<div class="rbox">
	<h3>
		<phrase id="<?=strtoupper($table)?>"/>
	</h3>
	<form class="ajax validate parent_reload" method="post" action="<?=FrontController::GetLink('savepreset')?>">
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

			</th>
		</tr>
		<tr>
			<?php foreach($keys as $key):?>
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
								<input type="text" name="<?=$key?>"/>
							<?php endif;?>
					</td>
				<?php endif;?>
			<?php endforeach;?>
			<?php if(isset($device_id) && isset($tab)):?>
				<input type="hidden" name="device_id" value="<?=$device_id?>"/>
				<input type="hidden" name="tab" value="<?=$tab?>"/>
			<?php endif;?>
			<input type="hidden" name="table" value="<?=$table?>"/>
			<input type="hidden" name="detail" value="<?=$specific?>"/>
			<td>
				<button type="submit" class="save">
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
</div>
<script>

   $('.parent_reload').submit(function()
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

   $('button.cancel').live('click', function(){
   		Shadowbox.close();
		return false;
	});
</script>