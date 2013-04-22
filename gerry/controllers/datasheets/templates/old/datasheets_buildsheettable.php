<?php foreach ($table as $table_name => $table_content):?>
					<form id="<?=$table_name?>_form" class="validate ajax reload_tab" id="form_<?=substr($table_name,0,3)?>_<?=substr($table_name,0,3)?>_form" method="POST" action="<?=FrontController::GetLink('savesheet', array('device_id' => $device_id, 'tab' => $tab, 'table' => $table_name))?>">
						<dl id="<?=substr($tab,0,3)?>_<?=substr($table_name,0,3)?>_dl">
							<?php foreach ($table_content as $field):?>
								<?php if(is_array($field)):?>
									<?php foreach ($field as  $input):?>
										<?php if(!is_array($input)):?>
											<?=$input?>
										<?php else:?>
											<?php if(isset($input['label'])):?>
												<dt class="<?=substr($page,0,3)?>_<?=substr($table,0,3)?>_label">
													<?=$input['label']?>
												</dt>
											<?php else:?>
												<dt/>
											<?php endif;?>
											<?php if(isset($input['input'])):?>
												<dd class="<?=substr($page,0,3)?>_<?=substr($table,0,3)?>_input">
													<?php if(!is_array($input['input'])):?>
														<?=$input['input']?>
													<?php else:?>
														<?php foreach($input['input'] as $in):?>
															<?=$in?>
														<?php endforeach;?>
													<?php endif;?>
												</dd>
											<?php endif?>
										<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
							<?php endforeach;?>
							<?php if(Youser::Id()):?>
								<dt/>
								<dd class="<?=substr($tab,0,3)?>_<?=substr($table_name,0,3)?>_save">
									<input type="submit" class="<?=$table_name?>_edit" value_phrase="SAVE"/>
								</dd>
							<?php endif;?>
						</dl>
					<?php if(Youser::Id()):?>
					</form>
					<?php endif;?>
<?php endforeach;?>