<div class="rbox">
<table class="edit_component">
	<?$tr = 0?>
	<tr class="r<?=$tr?> a<?=$tr++%2?>">
		<th colspan="2" class="r0 a0">
			<div class="th">
				<phrase id="<?=strtoupper($table)?>"/>
			</div>
		</th>
	</tr>
	<?php if(!Youser::Id()):?>
		<tr class="r<?=$tr?> a<?=$tr++%2?>">
			<td colspan="2" class="r0 a0">
				<div>
					<phrase id="BECOME_A_PART_OF_YOUSERBASE"/>
					
				</div>							
				<div style="text-align:center">
					<b><phrase id="NOT_LOGGED_IN_EDITS_SAVED_AS_GUEST"/></b>
						<a class="lightbox" href="<?=FrontController::GetLink('register', array('return_to' => $return_to))?>">
						<phrase id="LOGIN"/> <phrase id="OR"/> <phrase id="REGISTER""/>
					</a>
				</div>
			</td>
		</tr>
	<?php endif;?>
	<tr class="r<?=$tr?> a<?=$tr++%2?>">
	<td colspan="2">
		<?php if(isset($component_data)):?>
			<form method="post" class="<?=$admin?'':'ajax'?> changed edit_component stored:device_id:<?=$device_id?> stored:table:<?=$table?> stored:tab:<?=$tab?> stored:tbodies:<?=$tbodies?>" id="<?=$table?>" action="<?=FrontController::GetLink('savesheet', array('table' => $table, 'tab' => $tab, 'device_id' => $device_id, 'return_to' => $return_to))?>">
				<table>
				<?php if($table != 'market_information' && $table != 'reviews'):?>
					<tr class="r<?=$tr?> a<?=$tr++%2?>">
						<td class="r<?=$td=0?> <?=$td++%2?>">
							<phrase id="BUILD_IN"/>
						</td>
						<td class="r<?=$td?> <?=$td++%2?>">
							<select name="build_in">
								<option value="unknown"> <phrase id="UNKNOWN" quiet="true"/> </option>
								<option selected="selected" value="yes"> <phrase id="YES" quiet="true"/> </option>
								<option value="no"> <phrase id="NO" quiet="true"/> </option>
							</select>
						</td>
					</tr>
				<?php endif;?>
					<?php foreach ($component_data as $table_data):?>
						<?php if(!isset($table_data['description'])):?>
							<?php foreach ($table_data as $table_name => $data):?>
								<?php if(isset($data['description'])):?>
									<?php foreach($data['description'] as $line_name => $line_type):?>
										<?php if($line_type !== 'hidden' && $line_name !== 'youser_id' && $line_name !== 'timestamp' && strpos($line_name, '_type_id_int') === false):?>
											<tr class="r<?=$tr?> a<?=$tr++%2?>">
												<td class="r<?=$td=0?> <?=$td++%2?>">
													<?php if(strpos($line_name, '_units_type_id') === false && strpos($line_name, '_size_type_id') === false && strpos($line_name, 'currency_type_id') === false):?>
														<phrase id="<?=strtoupper($line_name)?>"/>
													<?php endif;?>
												</td>
												<td class="r<?=$td?> <?=$td++%2?>">
													<?php if($line_type == 'select' || $line_type == 'multiple' || $line_type == 'notshown'):?>
														<select <?=$line_type == 'multiple'?'name="'.$line_name.'[]" multiple="multiple">':'name="'.$line_name.'">'?>>
															<?php foreach ($data['preset'][$line_name] as $line):?>	
																<option value="<?=$line[$line_name]?>" <?=isset($data['data'][$line_name])&&in_array($line[$line_name], $data['data'][$line_name])?'selected=""selected':'';?>>
																	<phrase id="<?=$line[str_replace('type_id', 'type_name', $line_name)]?>" quiet="true"/>
																	<?php if(isset($line[str_replace('type_id', 'type_version', $line_name)]) && strpos(str_replace('type_id', 'type_version', $line_name), '_id') === false):?>
																	<phrase id="<?=$line[str_replace('type_id', 'type_version', $line_name)]?>" quiet="true"/>
																	<?php elseif(isset($line[str_replace('type_id', 'type_standard', $line_name)]) && strpos(str_replace('type_id', 'type_standard', $line_name), '_id') === false):?>
																		<?=is_numeric($line[str_replace('type_id', 'type_standard', $line_name)])?numberformat($line[str_replace('type_id', 'type_standard', $line_name)], 1, '.'):'<phrase id="'.$line[str_replace('type_id', 'type_standard', $line_name)].'" quiet="true"/>'?>
																	<?php endif;?>
																</option>
															<?php endforeach;?>
														</select>
													<?php elseif($line_type == 'text'):?>
														<input type="text" class="number" name="<?=$line_name?>"  value="<?=isset($data['data'][$line_name])?reset($data['data'][$line_name]):''?>"/>
													<?php elseif($line_type == 'textfield'):?>
														<textarea>
														</textarea>
													<?php endif;?>
												</td>
											</tr>
										<?php elseif($line_type == 'hidden'):?>
											<input type="hidden" name="<?=$table_name?>_<?=$line_name?>" value="<?=isset($data['data'][$line_name])?reset($data['data'][$line_name]):''?>"/>
										<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
							<?php endforeach;?>
						<?php else:?>
							<?php foreach($table_data['description'] as $line_name => $line_type):?>
								<?php if($line_type !== 'hidden' && $line_name !== 'youser_id' && $line_name !== 'timestamp' && strpos($line_name, '_type_id_int') === false && !($line_name === 'cdma_networks_type_id' && BabelFish::GetLanguage() === 'us')):?>
									<tr class="r<?=$tr?> a<?=$tr++%2?>">
										<td class="r<?=$td=0?> <?=$td++%2?>">
											<?php if(strpos($line_name, '_units_type_id') === false && strpos($line_name, '_size_type_id') === false && strpos($line_name, 'currency_type_id') === false):?>
												<phrase id="<?=strtoupper($line_name)?>"/>
											<?php endif;?>
										</td>
										<td class="r<?=$td?> <?=$td++%2?>">
											<?php if($line_type == 'select' || $line_type == 'multiple' || $line_type == 'notshown'):?>
												<select <?=$line_type == 'multiple'?'name="'.$line_name.'[]" multiple="multiple">':'name="'.$line_name.'">'?>>
													<?php foreach ($table_data['preset'][$line_name] as $line):?>	
														<option value="<?=$line[$line_name]?>" <?=isset($table_data['data'][$line_name]) && in_array($line[$line_name], $table_data['data'][$line_name])?'selected=""selected':'';?>>
															<phrase id="<?=$line[str_replace('_id', '_name', $line_name)]?>" quiet="true"/>
															<?php if(isset($line[str_replace('type_id', 'type_version', $line_name)]) && strpos(str_replace('type_id', 'type_version', $line_name), '_id')===false):?>
																<phrase id="<?=$line[str_replace('type_id', 'type_version', $line_name)]?>" quiet="true"/>
															<?php elseif(isset($line[str_replace('type_id', 'type_standard', $line_name)]) && strpos(str_replace('type_id', 'type_standard', $line_name), '_id')===false):?>
																<?=is_numeric($line[str_replace('type_id', 'type_standard', $line_name)])?numberformat($line[str_replace('type_id', 'type_standard', $line_name)], 1, '.'):'<phrase id="'.$line[str_replace('type_id', 'type_standard', $line_name)].'" quiet="true"/>'?>
															<?php endif;?>
														</option>
													<?php endforeach;?>
												</select>
											<?php elseif($line_type == 'text'):?>
												<input type="text" class="number" name="<?=$line_name?>" value="<?=isset($table_data['data'][$line_name])?reset($table_data['data'][$line_name]):''?>"/>
											<?php elseif($line_type == 'textfield'):?>
													<textarea>
													</textarea>
											<?php endif;?>
										</td>
									</tr>
								<?php elseif($line_type == 'hidden'):?>
									<input type="hidden" name="<?=$table?>_<?=$line_name?>" value="<?=isset($table_data['data'][$line_name])?reset($table_data['data'][$line_name]):''?>"/>
								<?php endif;?>
							<?php endforeach;?>
						<?php endif;?>
					<?php endforeach;?>
					<input type="hidden" name="return_to" value="<?=$return_to?>"/>
					<input type="hidden" name="changed" value="true"/>
					<tr class="r<?=$tr?> a<?=$tr++%2?>">
						<td colspan="2" style="text-align:center;">
							<button class="submit">
								<span>
									<phrase id="SAVE" quiet="true"/>
								</span>
							</button>
							<button class="cancel">
								<span>
									<phrase id="CANCEL" quiet="true"/>
								</span>
							</button>
						</td>
					</tr>
				</table>
			</form>
		<?php endif;?>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
//<![CDATA[

$('form.ajax.edit_component').livequery('submitted', function () {
	if($('form.ajax.edit_component').hasClass('changed'))
	{
		var table = $(this).attr('id');
		Shadowbox.close();
		$('.'+table).fadeOut('slow');
		var device_id = $(this).retrieve('device_id');
		var table = $(this).retrieve('table');
		var tab = $(this).retrieve('tab');
		var tbodies = $(this).retrieve('tbodies');
			$('.'+table)

			.load('datasheets/datasheets/viewcomponent?device_id='+device_id+'&table='+table+'&tab='+tab+'&tbodies='+tbodies);
		$('.'+table).fadeIn('slow');
	}
	
});
//]]>
</script>