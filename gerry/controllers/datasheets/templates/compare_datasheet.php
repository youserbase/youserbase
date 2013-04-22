<?php
$url = DBManager::Get('devices')->query('SELECT url FROM device_urls WHERE device_id = ? AND lang = ? AND tab = ?;', $compare_id, BabelFish::GetLanguage(), $tab)->fetch_item();
	if($url == null){
		$url = file_get_contents("http://tinyurl.com/api-create.php?url=".FrontController::GetURL()); 
		DBManager::Get('devices')->query("INSERT INTO device_urls (url, device_id, lang, tab) VALUES(?, ?, ?, ?)", $url, $compare_id, BabelFish::GetLanguage(), $tab);
		}
?>
<div class="phonesheet">
<table border="solid black 1px">
	<thead>
		<tr class="r<?=$trows = 0?> a<?=$trows++ % 2?>">
			<th class="r<?=$ths = 0?> a<?=$ths++ % 2?>">
				<div class="tweet_this">
					<a href="http://twitter.com/home?source=youserbase.org&status=From%20@youserbase:%20<?=$meta?> <?= $url?>" target="_blank">
						<img src="<?=Assets::Image('Twitter.png')?>" alt="Tweet_This" height="30px">weet
					</a>
				</div>
				<?php if(Youser::Is('administrator', 'root', 'god')):?>
					<meta property="og:image" content="<?=DeviceHelper::GetImage(reset($device_ids), 'avatar')?>"/>
				<?php endif;?>
				<fb:like href="<?=str_replace('tab='.$tab.'&', '', FrontController::GetUrl())?>" layout="button_count" show_faces="true" width="160" action="like" colorscheme="light" />
			</th>
			<?php foreach($device_ids as $device_id):?>
				<th class="r<?=$ths?> a<?=$ths++ % 2?>">
					<div class="device_header">
						<div class="device_data" style="text-align:center;">
								<device id="<?=$device_id?>"/>
						</div>
						<div class="device_picure" style="text-align:center;">
							<device id="<?=$device_id?>" type="medium"/>
						</div>
						<?php if (isset($rating[$device_id])): ?>
							<div class="device_rating">
								<?=$this->render_partial('rating', array('name'=>'device_rating', 'rating'=>$rating[$device_id]['device_rating'], 'device_id' => $device_id, 'sheet_tab' => $tab, 'tab' => 'device_rating', 'table' => '', 'feature' => '', 'compare' => 'true', 'dimension' => 'big'))?>
							</div>
						<?php endif;?>
					</div>
				</th>
			<?php endforeach;?>
		</tr>
	</thead>
	<?php if($tab === 'Comments'):?>
		<tbody>
			<tr>
				<td colspan="<?=count($device_ids)+1;?>">
					<?=Controller::Render('datasheets', 'Datasheets_Comments', 'Index', array('compare_id' => $compare_id))?>
				</td>
			</tr>
		</tbody>
	<?php else:?>
			<?php $tbodies=0?>
			<?php foreach ($data[reset($device_ids)][$tab] as $component_name => $component_data):?>
			<tbody class="r<?=$tbodies?> a<?=$tbodies++%2?> hoverable">
				<tr class="r<?=$trows = 0?> a<?=$trows++ % 2?>">
					<th class="r<?=$ths = 0?> a<?=$ths++ % 2?>">
						<div class="th">
							<phrase id="<?=strtoupper($component_name)?>"/>
						</div>
					</th>
					<?php foreach ($device_ids as $device_id):?>
						<th class="r<?=$ths?> a<?=$ths++ % 2?>">
							<div class="th">
								<?php if (isset($rating[$device_id]['table_rating'][$component_name])):?>
									<?=$this->render_partial('rating', array('name'=>$component_name, 'rating'=>$rating[$device_id]['table_rating'][$component_name], 'device_id' => $device_id, 'sheet_tab' => $tab, 'tab' => $tab, 'table' => $component_name, 'feature' => 'null', 'compare' => 'true', 'dimension' => 'small'))?>
								<?php endif;?>
							</div>
						</th>
					<?php endforeach;?>
				</tr>
				<?php foreach ($component_data as $key => $value):?>
					<?php if(!is_numeric($key) && $key !== 'youser_id' && $key !== 'timestamp' && strpos($key, '_type_id_int')=== false):?>
						<tr class="r<?=$trows?> a<?=$trows++ % 2?>">
							<td class="r<?=$tds = 0?> a<?=$tds++ % 2?>">
								<phrase id="<?=strtoupper($key)?>"/>
							</td>
							<?php foreach ($device_ids as $device_id):?>
								<td class="r<?=$tds?> a<?=$tds++ % 2?>">
									<?php if($data[$device_id][$tab][$component_name][$key] !== $key):?>
										<?php if(isset($data[$device_id][$tab][$component_name][$key]['value'])):?>
											<div>
												<?=$data[$device_id][$tab][$component_name][$key]['value']?>
												<?=isset($data[$device_id][$tab][$component_name][$key]['unit'])?'<phrase id="'.$data[$device_id][$tab][$component_name][$key]['unit'].'"/>':'<phrase id="UNKNOWN"/>'?> 
											</div>
										<?php else:?>
											<?php foreach ($data[$device_id][$tab][$component_name][$key] as $unit => $line):?>
												<?php if(!empty($line)):?>
													<div>
														<?=is_numeric($line)?$line:'<phrase id="'.strtoupper($line).'"/>'?>
													</div>
												<?php else:?>
													<phrase id="UNKNOWN"/>
												<?php endif;?>
											<?php endforeach;?>
										<?php endif;?>
									<?php else:?>
										<phrase id="UNKNOWN"/>
									<?php endif;?>
								</td>
							<?php endforeach;?>
						</tr>
					<?php elseif(is_array($value)):?>
						<?php foreach ($value as $attrib => $val):?>
							<?php if($attrib !== 'youser_id' && $attrib !== 'timestamp' && strpos($attrib, '_type_id_int')=== false):?>
								<tr>
									<td class="r<?=$tds = 0?> a<?=$tds++ % 2?>">
										<phrase id="<?=strtoupper($attrib)?>"/>
									</td>
									<?php foreach ($device_ids as $device_id):?>
										<td class="r<?=$tds?> a<?=$tds++ % 2?>">
											<?php if(is_array($data[$device_id][$tab][$component_name][$key][$attrib])):?>
												<?php if (isset($data[$device_id][$tab][$component_name][$key][$attrib]['value'])):?>
													<div>
														<?=$data[$device_id][$tab][$component_name][$key][$attrib]['value']?>
														<?=isset($data[$device_id][$tab][$component_name][$key][$attrib]['unit'])?'<phrase id="'.$data[$device_id][$tab][$component_name][$key][$attrib]['unit'].'"/>':'<phrase id="UNKNOWN"/>'?>
													</div>
												<?php elseif (isset($data[$device_id][$tab][$component_name][$key][$attrib]['name'])):?>
													<div>
														<phrase id="<?=$data[$device_id][$tab][$component_name][$key][$attrib]['name']?>"/>
														<?=isset($data[$device_id][$tab][$component_name][$key][$attrib]['unit'])?'<phrase id="'.$data[$device_id][$tab][$component_name][$key][$attrib]['unit'].'"/>':''?>
													</div>
												<?php else:?>
													<?php foreach ($data[$device_id][$tab][$component_name][$key][$attrib] as $a => $line):?>
														<?php if(!empty($line)):?>
															<div>
																<?=is_numeric($line)?$line:'<phrase id="'.strtoupper($line).'"/>'?>
															</div>
														<?php else:?>
															<phrase id="UNKNOWN"/>
														<?php endif;?>
													<?php endforeach;?>
												<?php endif;?>
											<?php else:?>
												<?php if($data[$device_id][$tab][$component_name][$key][$attrib] === $attrib):?>
													<phrase id="UNKNOWN"/>
												<?php else:?>
													<?=is_numeric($data[$device_id][$tab][$component_name][$key][$attrib])?$data[$device_id][$tab][$component_name][$key][$attrib]:'<phrase id="'.strtoupper($data[$device_id][$tab][$component_name][$key][$attrib]).'"/>'?>
												<?php endif;?>
											<?php endif;?>
										</td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
			</tbody>
		<?php endforeach;?>
		<?php endif;?>
</table>
<page id="disclaimer"/>
</div>