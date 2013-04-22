<table border>
	<tr>
	<?php foreach($sheets as $counter => $sheet):?>
	<td colspan="2">
		<?=$headsheet[$counter]?>
	</td>
	<?php endforeach;?>
	</tr>
	<tr>
	<?php foreach($sheets as $counter => $sheet):?>
		<td>
			<?php foreach ($sheet as $page => $pagedata):?>
				<?if(is_array($pagedata)):?>
					<?php foreach ($pagedata as $table => $fields):?>
					<h3 id="<?substr($page,0,3)?>_h" class="accordion" for="<?=strtoupper($table)?>"></h3>
						<dl>
							<?php foreach ($fields as $field):?>
								<?php if(is_array($field)):?>
									<?php foreach ($field as  $input):?>
										<?php if(!is_array($input)):?>
											<?=$input?>
										<?php else:?>
											<?php if(isset($input['label']) && !is_array($input['label'])):?>
												<dt id="<?=substr($page,0,3)?>_<?=substr($table,0,3)?>_label">
													<label for="<?=$input['label']?>"><phrase id="<?=strtoupper($input['label'])?>"/></label>
												</dt>
											<?php else:?>
												<dt/>
											<?php endif;?>
											<?php if(isset($input['input'])):?>
												<dd id="<?=substr($page,0,3)?>_<?=substr($table,0,3)?>_input">
													<ul>
														<?php if(!is_array($input['input'])):?>
														<li>
															<?=$input['input']?>
														</li>
														<?php else:?>
														<?php foreach($input['input'] as $option):?>
															<?php if(!is_array($option)):?>
																<li>
																	<?=$option?>
																</li>
															<?php endif;?>
														<?php endforeach;?>
														<?php endif;?>
													</ul>
												</dd>
											<?php endif;?>
										<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
							<?php endforeach;?>
						</dl>
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
		</td>
		<td>
			<?php foreach ($sheet as $page => $pagedata):?>
				<?if(is_array($pagedata)):?>
					<?php foreach ($pagedata as $table => $fields):?>
						<?php if(isset($ratings[$counter][strtolower($table)])):?>
							<?php foreach ($ratings[$counter][strtolower($table)] as $name => $rating):?>
								<dl class="ratings zebra">
									<?php if ($name == 'avg'):?>
										<?$name = $table.'_'.$name?>
									<?php endif;?>
									<?=isset($rating)?$this->render_partial('rating', array('name'=>$name, 'rating'=>$rating)):''?>
								</dl>
							<?php endforeach;?>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
		</td>
	<?php endforeach;?>
	</tr>
</table>