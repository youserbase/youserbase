<h3><phrase id="LAST_ACTIVITIES_ON_YOUSERBASE"/>:</h3>
<?php if (!empty($activities)): ?>
<ul id="activity_list">
<?php $i=0; foreach ($activities as $activity): ?>
	<li class="r<?=$i?> a<?=$i++%2?> <?=strtolower($activity['activity'])?>">
		<div class="date"><?=date('d.m.Y H:i:s', $activity['timestamp'])?></div>
		<?=$this->render_partial('activity_'.strtolower($activity['activity']), $activity)?>
	</li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>
	<phrase id="NOTHINGHAPPENED"/>
</p>
<?php endif; ?>