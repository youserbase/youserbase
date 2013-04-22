<div class="rbox unconfirmed_components">
	<h3>
	<?=$components_count?> <?=$components_count==1?'<phrase id="DEVICE_WITH_UNCONFIRMED_COMPONENTS"/>':'<phrase id="DEVICES_WITH_UNCONFIRMED_COMPONENTS"/>'?>
	</h3>
	<?php if(isset($device_components_ids) && is_array($device_components_ids)):?>
		<?php $counter = 1+$skip_components?>
		<div class="pagination">
			<?php if($skip_components >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => -10))?>">
			<?php endif;?>
					<phrase id="FIRST"/>
			<?php if($skip_components >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components >= 10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components-20))?>">
			<?php endif;?>
				<phrase id="PREVIOUS"/>
			<?php if($skip_components >= 10):?>
				</a>
			<?php endif;?>
			<?php if($skip_components <= $components_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components))?>">
			<?php endif?>
				<phrase id="NEXT"/>
			<?php if($skip_components <= $components_count-10):?>
				</a>
			<?php endif;?>
			<?php if($skip_components != $components_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $components_count-20))?>">
			<?php endif?>
				<phrase id="LAST"/>
			<?php if($skip_components != $components_count-10):?>
				</a>
			<?php endif?>
		</div>
		<?php foreach ($device_components_ids as $device_id => $component_data):?>
			<form class="<?=$device_id?>" method="post" action="<?=FrontController::GetLink('datasheets', 'admin', 'confirm', array('device_id' => $device_id))?>">
				<table>
					<thead>
						<tr>
							<th>
								<?=$counter?>
							</th>
							<th>
								<?php $counter++?> <phrase id="CONFIRM"/>
							</th>
							<th>
								<device id="<?=$device_id?>"/>
							</th>
							<th>
								<div class="expand f<?=$device_id?>" id="f<?=$device_id?>"/>
									<phrase id="EXPAND"/>
								</div>
								<div class="expand f<?=$device_id?>" id="f<?=$device_id?>" style="display:none;"/>
									<phrase id="COLLAPSE"/>
								</div>
							</th>
						</tr>
					</thead>				
					<tbody class="f<?=$device_id?>" style="display:none;">
						<?php foreach ($component_data as $table_name => $data):?>
							<?php if(isset($data[$table_name]['data']['component_id'])):?>
								<tr>
									<th>
										<input type="checkbox" class="a<?=$device_id?>" name="table_name[]" value="<?=$table_name?>,<?=is_array($data[$table_name]['data']['component_id'])?reset($data[$table_name]['data']['component_id']):$data[$table_name]['data']['component_id']?>"/>
										<a class="lightbox" href="<?=FrontController::GetLink('datasheets', 'datasheets','editcomponent', array('table' => $table_name, 'device_id' => $device_id, 'return_to' => FrontController::GetURL(BabelFish::GetLanguage())))?>"><phrase id="EDIT"/></a>
									</th>
									<th colspan="2">
										<phrase id="<?=strtoupper($table_name)?>"/>
									</th>
									<th>
										<?php if(isset($data[$table_name]['data']['youser_id'])):?>
											<youser id="<?=$data[$table_name]['data']['youser_id']?>"/>
											<?=$data[$table_name]['data']['timestamp']?>
										<?php endif;?>
									</th>
								</tr>
								<?php if(is_array($data[$table_name]['data'])):?>
									<?php foreach ($data[$table_name]['data'] as $line_name => $line_content):?>
										<?php if($line_name !== $table_name.'_id' && $line_name != 'timestamp' && strpos($line_name,'component_id')===false && $line_name !== 'youser_id' && strpos($line_name, '_type_id')===false):?>
											<tr>
												<td></td>
												<td colspan="2">
													<?=$line_name?>
												</td>
												<td>
													<?php if (is_array($line_content)):?>
														<?php foreach ($line_content as $value):?>
															<?php if($value == null):?>
																<div><phrase id="UNKNOWN"/></div>
															<?php else:?>
																<div><?=is_numeric($value)?$value:'<phrase id="'.strtoupper($value).'"/>'?></div>
															<?php endif;?>
														<?php endforeach;?>
													<?php endif;?>
												</td>
											</tr>
										<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
							<?php endif;?>
						<?php endforeach;?>
						<tr>
							<td>
								<div class="select_all a<?=$device_id?>" id="a<?=$device_id?>">
									<img src="<?=Assets::Image('famfamfam/accept.png')?>" alt="select_all"/> <phrase id="SELECT_ALL"/>
								</div>
								<div class="deselect_all a<?=$device_id?>" id="a<?=$device_id?>" style="display:none">
									<img src="<?=Assets::Image('famfamfam/cancel.png')?>" alt="select_all"/> <phrase id="DESELECT_ALL"/>
								</div>
							</td>
							<td colspan="2">
								<button>
									<span>
										<phrase id="UPDATE_SELECTED"/>
									</span>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		<?php endforeach;?>
		<div class="pagination">
			<?php if($skip_components >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => -10))?>">
			<?php endif;?>
					<phrase id="FIRST"/>
			<?php if($skip_components >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components >= 10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components-20))?>">
			<?php endif;?>
				<phrase id="PREVIOUS"/>
			<?php if($skip_components >= 10):?>
				</a>
			<?php endif;?>
			<?php if($skip_components <= $components_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components))?>">
			<?php endif?>
				<phrase id="NEXT"/>
			<?php if($skip_components <= $components_count-10):?>
				</a>
			<?php endif;?>
			<?php if($skip_components != $components_count-10):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $components_count-20))?>">
			<?php endif?>
				<phrase id="LAST"/>
			<?php if($skip_components != $components_count-10):?>
				</a>
			<?php endif?>
		</div>
	<?php else:?>
		<div class="nothig_to_do">
			<phrase id="NO_UNCONFIRMED_COMPONENTS"/>
		</div>
	<?php endif;?>
</div>