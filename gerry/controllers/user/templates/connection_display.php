<?php // TODO: Tab über Partial ?>
<div class="flora">
	<ul class="tabify">
		<li>
		<?php if ($youser->id!=Youser::Id()): ?>
			<a href="<?=FrontController::GetLink('Display_All', array('youser_id'=>$youser->id))?>" title="All">
		<?php else: ?>
			<a href="<?=FrontController::GetLink('Display_All')?>" title="All">
		<?php endif; ?>
				<span>
					<phrase id="ALL_FRIENDS"/>
					(<?=numberformat($friend_count)?>)
				</span>
			</a>
		</li>
	</ul>
</div>