<div class="rbox">
	<h3>
		<?php if(isset($table)):?>
			<phrase id="RATINGS_FOR_<?=strtoupper($table)?>"/>
			<a class="lightbox" href="<?=FrontController::GetLink('edit', array('table' => $table, 'device_id' => $device_id))?>">
		<?php elseif(isset($tab) && $tab != 'device_rating'):?>
			<phrase id="RATINGS_FOR_<?=strtoupper($tab)?>"/>
			<a class="lightbox" href="<?=FrontController::GetLink('edit', array('tab' => $tab, 'device_id' => $device_id))?>">
		<?php else:?>
			<phrase id="RATINGS_FOR"/> <device id="<?=$device_id?>"/>
			<a class="lightbox" href="<?=FrontController::GetLink('edit', array('tab' => $tab, 'device_id' => $device_id))?>">
		<?php endif;?>
		<phrase id="YOUR_RATING"/></a>
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
					<div class="ratingstar">
						<?php if(isset($table_rating)):?>
							<div style="width:<?=$table_rating?>%">
								&nbsp;
							</div>
						<?php elseif(isset($tab_rating)):?>
							<div style="width:<?=$tab_rating?>%">
								&nbsp;
							</div>
						<?php else:?>
							<div style="width:<?=$device_rating?>%">
								&nbsp;
							</div>
						<?php endif;?>
					</div>
				</td>
			</tr>
		</thead>
		<?php if (isset($feature_rating)):?>
			<tbody>
				<?php foreach($feature_rating as $feature => $rating):?>
					<tr>
						<th>
							<label for="<?=$feature?>">
								<phrase id="<?=$feature?>"/>
							</label>
						</th>
						<td>
							<div class="ratingstar">
								<div style="width:<?=$rating?>%">
									&nbsp;
								</div>
							</div>
						</td>
					</tr>	
				<?php endforeach;?>
			</tbody>
		<?php endif;?>
	</table>
</div>