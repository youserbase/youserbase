<div class="rbox">
	<h2>
		<phrase id="VISIT_STATS"/>
	</h2>
	<div class="form">
		<form action="<?=FrontController::GetLink('change_time')?>" method="post">
			<div class="input">
				<label for="date"><phrase id="FROM"/></label>
				<input type="text" name="year" value="<?=$year?>" size="6"/>
				<input type="text" name="month" value="<?=$month?>" size="4"/>
				<input type="text" name="day" value="<?=$day?>" size="4"/>
			</div>
			<div class="input">
				<label for="date"><phrase id="UNTIL"/></label>
				<input type="text" name="year_to" value="<?=$year_until?>" size="6"/>
				<input type="text" name="month_to" value="<?=$month_until?>" size="4"/>
				<input type="text" name="day_to" value="<?=$day_until?>" size="4"/>
			</div>
			<div>
				<button class="submit">
					<span>
						<phrase id="UPDATE" quiet="true"/>
					</span>
				</button>
			</div>
		</form>
	</div>
	<?php if(isset($visits)):?>
	<div class="floatbox">
	    <?php if ($skip > 0): ?>
	    <div class="fleft">
	        <a href="<?=FrontController::GetLink('skip', array('skip_stats' => $skip-$limit))?>" class="target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/></a>
	    </div>
	    <?php endif; ?>
	    <?php if ($skip + $limit < $total): ?>
	    <div class="fright">
	        <a href="<?=FrontController::GetLink('skip', array('skip_stats' => $skip+$limit))?>" class="target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/></a>
	    </div>
	    <?php endif; ?>
	</div>
		<div class="stats">
			<phrase id="PAGEVIEWS"/> <?=$total?><br/>
			<phrase id="UNIQUE_VISITS"/> <?=$unique?>
		</div>
		<table>
			<tr>
				<th>
					<phrase id="TIME"/>
				</th>
				<th>
					<phrase id="DEVICE"/>
				</th>
				<th>
					<phrase id="TAB"/>
				</th>
				<th>
					<phrase id="SOURCE"/>
				</th>
				<th>
					<phrase id="LANGUAGE"/>
				</th>
			</tr>
		<?php foreach ($visits as $visit):?>
			<tr class="visit">
				<td>
					<?= dateformat($visit['timestamp'])?>
				</td>
				<td>
					<device id="<?=$visit['device_id']?>"/>
				</td>
				<td>
					<phrase id="<?=$visit['tab']?>"/>
				</td>
				<td>
					<?=is_numeric($visit['youser_id'])!=false?'<youser id="'.$visit['youser_id'].'"/>':'<a href="http://www.utrace.de/?query='.$visit['youser_id'].'" target="_blank">'.$visit['youser_id'].'</a>'?>
				</td>
				<td>
					<img src="<?=Assets::Image('flags/'.$visit['language'].'.png')?>" alt_phrase="<?=$visit['language']?>" title_phrase="<?=$visit['language']?>" />
				</td>
			</tr>
		<?php endforeach;?>
		</table>
	<?php endif;?>
</div>