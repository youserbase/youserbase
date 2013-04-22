<div class="rbox">
<h3>
	<?php if(isset($table)):?>
		<phrase id="RATINGS_FOR_<?=strtoupper($table)?>"/>
		
	<?php elseif(isset($tab) && $tab != 'device_rating'):?>
		<phrase id="RATINGS_FOR_<?=strtoupper($tab)?>"/>
		
	<?php else:?>
		<phrase id="RATINGS_FOR"/> <device id="<?=$device_id?>"/>
	<?php endif;?>
	<img src="<?=Assets::Image('famfamfam/help.png')?>" alt_phrase="help" title_phrase="help" class="rating_help"/>
</h3>
<table>
	<thead>
		<tr>
			<th>
			<?php if(isset($table)):?>
				<phrase id="<?=strtoupper($table)?>"/>
			<?php elseif(isset($tab) && $tab != 'device_rating'):?>
				<phrase id="<?=strtoupper($tab)?>"/>
			<?php else:?>
				<device id="<?=$device_id?>"/>
			<?php endif;?>
			</th>
			<td>
				<div class="ratingedit">
					<?php foreach($rating_steps as $rating => $meaning):?>
						<?php if(isset($table)):?>
						<a class="lightbox rating_box" href="<?=FrontController::GetLink('save', array('device_id' => $device_id, 'table' => $table, 'rating' => $rating, 'sheet_tab' => $sheet_tab))?>">
							<?php if($rating/5*100 <= $table_rating):?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php else:?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star_grey.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php endif;?>
						<?php elseif(isset($tab_rating)):?>
							<a class="lightbox rating_box" href="<?=FrontController::GetLink('save', array('device_id' => $device_id, 'tab' => $tab, 'rating' => $rating, 'sheet_tab' => $sheet_tab))?>">
							<?php if($rating/5*100 <= $tab_rating):?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php else:?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star_grey.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php endif;?>
						<?php else:?>
						<a class="lightbox rating_box" href="<?=FrontController::GetLink('save', array('device_id' => $device_id, 'tab' => $tab, 'rating' => $rating, 'sheet_tab' => $sheet_tab))?>">
							<?php if($rating/5*100 <= $device_rating):?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php else:?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star_grey.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php endif;?>
						<?php endif;?>
						</a>
					<?php endforeach;?>
					</div>
			</td>
		</tr>
	</thead>
	<?php if(isset($table)):?>
	<tbody>
		<?php foreach($feature_rating as $feature => $rating):?>
			<tr>
				<th>
					<label for="<?=$feature?>">
						<phrase id="<?=$feature?>"/>
					</label>
				</th>
				<td>
					<div class="ratingedit">
						<?php foreach($rating_steps as $rating => $meaning):?>
						<a class="lightbox rating_box" href="<?=FrontController::GetLink('save', array('device_id' => $device_id, 'table' => $table, 'feature' => $feature, 'rating' => $rating, 'sheet_tab' => $sheet_tab))?>">
							<?php if($rating/5*100 <= $feature_rating[$feature]):?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php else:?>
								<img class="feature" src="<?=Assets::Image('famfamfam/star_grey.png')?>" alt_phrase="<?=$meaning?>" title_phrase="<?=$meaning?>"/>
							<?php endif;?>
						</a>
					<?php endforeach;?>
					</div>
				</td>
			</tr>	
		<?php endforeach;?>
	</tbody>
	<?php endif;?>
</table>
<?php if(isset($sheet_tab)):?>
	<input type="hidden" name="sheet_tab" value="<?=$sheet_tab?>"/>
	<input type="hidden" name="device_id" value="<?=$device_id?>"/>
	<input type="hidden" name="link" value="<?=FrontController::GetLink('datasheets', 'datasheets', 'phonesheet')?>"/>
<?php endif;?>
<div class="help" style="display:none">
	<h3><phrase id="RATING_HELP"/></h3>
	<p>
	<phrase id="HERE_YOU_CAN_SEE_YOUR_RATING"/>
	<br/>
	<phrase id="YOU_MAY_RATE_A_FEATURE_BY_CLICKING_THE_STARS"/>
	</p>
</div>
</div>