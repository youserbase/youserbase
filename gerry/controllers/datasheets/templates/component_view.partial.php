<?php if($tbodies == 5):?>
	<?php if(strtolower($table) == 'gps_function'):?>
		<tbody class="r<?=$tbodies?> a<?=$tbodies++ % 2?> hoverable">
			<tr>
				<th colspan="2">
					<div class="warning">
						<phrase id="THE_FOLLOWING_INFORMATION_DEPENDS_ON_THE_INSTALLED_SOFTWARE"/>
					</div>
				</th>
			</tr>
		</tbody>
	<?php endif;?>
<?php endif;?>
<?php if(!isset($body)):?>
<tbody class="r<?=$tbodies?> a<?=$tbodies++%2?> hoverable  <?=$table?>">	
<?php endif;?>
	<tr class="r<?=$trows = 0?> a<?=$trows++ % 2?>">
		<th class="r<?=$ths = 0?> a<?=$ths++ % 2?>">
			<div class="th">
				<phrase id="<?=strtoupper($table)?>"/> 
			</div>
			<?php if (Youser::Is('root', 'administrator', 'god')):?>
			<?$edited = true?>
			<div class="NONE">
				<?php if(isset($contents['youser_id']) && $contents['youser_id'] !== 'youser_id'):?>
					<phrase id="LAST_EDIT_BY"/><br/>
					<youser id="<?=$contents['youser_id']?>"/>
					<br/><phrase id="ON"/>
					<?=dateformat(strtotime($contents['timestamp']))?>
					<?php $timestamp = $contents['timestamp'];?>
				<?php else:?>
					<?php if(isset($contents[0]['youser_id']) && $contents[0]['youser_id'] !== 'youser_id'):?>
						<phrase id="LAST_EDIT_BY"/><br/>
						<youser id="<?=$contents[0]['youser_id']?>"/>
						<br/><phrase id="ON"/>
						<?=dateformat(strtotime($contents[0]['timestamp']))?>
						<?php $timestamp = $contents[0]['timestamp'];?>
					<?php elseif(isset($contents[1]['youser_id']) && $contents[1]['youser_id'] !== 'youser_id'):?>
						<phrase id="LAST_EDIT_BY"/><br/>
						<youser id="<?=$contents[1]['youser_id']?>"/>
						<br/><phrase id="ON"/>
						<?=dateformat(strtotime($contents[1]['timestamp']))?>
						<?php $timestamp = $contents[1]['timestamp'];?>
					<?php else:?>
						<phrase id="NOT_EDITED"/>
						<?$edited = false?>
					<?php endif;?>
				<?php endif;?>
			</div>
			<div class="versions">
				<?php if($edited):?>
				
					<img class="versioning" title="<?=$table?>" id="<?=FrontController::GetLink('viewcomponent', array('device_id'=>$device_id, 'tab'=>$tab, 'table' => $table, 'tbodies' => $tbodies, 'skip' => $skip+1))?>" src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt_phrase="PREVOIUS"/>
				
				<?php endif;?>
				<?php if ($edited && $skip>0):?>
					<img class="versioning" title="<?=$table?>" id="<?=FrontController::GetLink('viewcomponent', array('timestamp' => $timestamp, 'device_id'=>$device_id, 'tab'=>$tab, 'table' => $table, 'tbodies' => $tbodies, 'skip' => 0))?>" src="<?=Assets::Image('famfamfam/accept.png')?>">
				<?php endif;?>
				<?php if($skip > 0):?>
					<img class="versioning" title="<?=$table?>" id="<?=FrontController::GetLink('viewcomponent', array('device_id'=>$device_id, 'tab'=>$tab, 'table' => $table, 'tbodies' => $tbodies, 'skip' => $skip-1))?>"src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt_phrase="NEXT"/>
				<?php endif;?>
				<?php if ($skip > 1):?>
					<img class="versioning" title="<?=$table?>" id="<?=FrontController::GetLink('viewcomponent', array('device_id'=>$device_id, 'tab'=>$tab, 'table' => $table, 'tbodies' => $tbodies, 'skip' => 0))?>"src="<?=Assets::Image('famfamfam/control_end.png')?>" alt_phrase="NEXT"/>
				<?php endif;?>
			</div>
			<?php endif;?>
		</th>
		<?php if(!isset($build_in[$table]) || $build_in[$table] !== 'no'):?>
			<th class="r<?=$ths?> a<?=$ths++ % 2?>">
				<?php if (isset($rating['table_rating'][$table])): ?>
					<div class="component_rating_canvas">
						<div class="component_rating">
							<?=$this->render_partial('rating', array('name'=>$table, 'rating'=>$rating['table_rating'][$table], 'device_id' => $device_id, 'sheet_tab' => $tab, 'tab' => $tab, 'table' => $table, 'feature' => 'null', 'dimension' => 'small'))?>
						
						</div>
					</div>
				<?php endif;?>
				<div class="edit_datasheet">
					<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('datasheets', 'Comments', 'Edit', array('device_id' => $device_id, 'category' => $table, 'return_to' => str_replace($tab, 'Comments', FrontController::GetURL()), 'return_to_false' => FrontController::GetURL()))?>"><phrase id="NEW_COMMENT"/> (<?=isset($comment_count[strtoupper($table)])?$comment_count[strtoupper($table)]:'0'?>)</a>
					<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id, 'return_to' => str_replace('/'.$tab, '#'.$tab, FrontController::GetURL(BabelFish::GetLanguage()))))?>"><phrase id="EDIT"/></a>
				</div>
			</th>
		</tr>
		<?php if(is_array($contents)):?>
				<?php foreach($contents as $attrib => $value):?>
					<?php if(!is_numeric($attrib) && $attrib != 'timestamp' && $attrib != 'youser_id' && strpos($attrib, '_type_id_') === false && strpos($attrib, '_type_standard') === false && strpos($attrib, '_type_version') === false && strpos($attrib, '_type_bandwith') === false):?>
						<tr class="r<?=$trows?> a<?=$trows++ % 2?>">
							<td class="r<?=$tds = 0?> a<?=$tds++ % 2?>">
								<phrase id="<?=strtoupper($attrib)?>"/>
							</td>
							<td class="r<?=$tds?> a<?=$tds++ % 2?>">
								<?php if($attrib == $value):?>
									<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id))?>"><phrase id="EDIT"/></a>
								<?php else:?>
									<?php if(isset($value['value'])):?>
										<?=$value['value']?> <phrase id="<?=$value['unit']?>"/>
									<?php elseif(isset($value['name'])):?>
										<phrase id="<?=$value['name']?>"/> <?=is_numeric($value['unit'])?numberformat($value['unit'], 1, '.'):'<phrase id="'.$value['unit'].'"/>'?>
									<?php else:?>
										<?php foreach($value as $key => $val):?>
											<div>
												<?php $return_to = FrontController::GetURL()?>
												<?php if(!empty($val)):?>
													<?php if($attrib !== 'battery_name'):?>
														<?php if(!is_array($val)):?>
												 		<?=is_numeric($val)?$val:'<phrase id="'.strtoupper($val).'"/>'?>
												 		<?php elseif(isset($val['name'])):?>
												 		<div>
												 			<?=is_numeric($val['name'])?$val['name']:'<phrase id="'.$val['name'].'"/>'?> <?=isset($val['unit'])?'<phrase id="'.$val['unit'].'"/>':''?>
												 		</div>
												 		<?php else:?>
													 		<?php foreach ($val as $v):?>
													 			<div>
													 				<?=$v?>
													 			</div>
													 		<?php endforeach;?>
												 		<?php endif;?>
													<?php else:?>
														<?=$val?>
													<?php endif;?>
												<?php else:?>
													<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id))?>"><phrase id="EDIT"/></a>
												<?php endif;?>
											</div>
										<?php endforeach;?>
									<?php endif;?>
								<?php endif;?>
							</td>
						</tr>
						<?php elseif(is_array($value)):?>
							<?php foreach($value as $key => $val):?>
								<?php if(!is_numeric($key) && $key != 'timestamp' && $key != 'youser_id' && strpos($key, '_type_id_') === false && strpos($key, '_type_standard') === false && strpos($key, '_type_version') === false && strpos($key, '_type_bandwith') === false):?>
									<tr class="r<?=$trows?> a<?=$trows++ % 2?>">
										<td class="r<?=$tds = 0?> a<?=$tds++ % 2?>">
											<phrase id="<?=strtoupper($key)?>"/>
										</td>
										<td class="r<?=$tds?> a<?=$tds++ % 2?>">
											<?php if(is_array($val)):?>
												<?php if(isset($val['value'])):?>
													<?=$val['value']?> <phrase id="<?=$val['unit']?>"/>
												<?php elseif(isset($val['name'])):?>
													<phrase id="<?=$val['name']?>"/> <?=is_numeric($val['unit'])?numberformat($val['unit'], 1, '.'):'<phrase id="'.$val['unit'].'"/>'?>
												<?php elseif(is_array($val)):?>
													<?php $edit = false;?>
													<?php foreach ($val as $entry):?>
														<?php if($entry === null || $entry == ''):?>
																<?php $edit = true;?>
															<?php else:?>
																<?php if(is_array($entry)):?>
																<div>
													<?=is_numeric($entry['name'])?$entry['name']:'<phrase id="'.strtoupper($entry['name']).'"/>'?> <?=isset($entry['name'])?'<phrase id="'.$entry['unit'].'"/>':''?>
																</div>
																<?php else:?>
																<div>
																	<?=is_numeric($entry)?$entry:'<phrase id="'.$entry.'"/>'?>
																</div>
															<?php endif;?>
															<?php endif;?>
													<?php endforeach;?>
													<?php if($edit):?>
														<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id))?>"><phrase id="EDIT"/></a>
													<?php endif;?>
												<?php endif;?>
											<?php elseif(!empty($val)):?>
													<?php if($key === $val || $val === null || $val == ''):?>
															<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id))?>"><phrase id="EDIT"/></a>
													<?php else:?>
														<?=is_numeric($val)?$val:'<phrase id="'.$val.'"/>'?>
													<?php endif;?>
											<?php else:?>
														<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id))?>"><phrase id="EDIT"/></a>
											<?php endif;?>
										</td>
									</tr>
								<?php endif;?>
							<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
			<?php else:?>
				<tr class="r<?=$trows?> a<?=$trows++ % 2?>">
					<td class="r<?=$tds = 0?> a<?=$tds++ % 2?>"></td>
					<td class="r<?=$tds?> a<?=$tds++ % 2?>">
						<phrase id="UNKNOWN"/>
					</td>
				</tr>
			<?php endif;?>
		<?php else:?>
			<th class="r<?=$ths?> a<?=$ths++ % 2?>">
				<div class="th component_build_in">
					<phrase id="NOT_BUILD_IN"/>
				</div>
				<div class="edit_datasheet" id="<?=$table?>" style="text-align:right;">
						<a class="lightbox" rel="nofollow" href="<?=FrontController::GetLink('editcomponent', array('tab' => $tab, 'table' => $table, 'device_id' => $device_id, 'return_to' => str_replace('/'.$tab, '#'.$tab, FrontController::GetURL(BabelFish::GetLanguage()))))?>"><phrase id="EDIT"/></a>
				</div>
			</th>
		</tr>
	<?php endif?>
<?php if(!isset($body)):?>
</tbody>
<?php endif?>