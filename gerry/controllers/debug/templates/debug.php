<?php if ($debug and !empty($console)): ?>
<div id="development_console">
	<pre><?=htmlentities($console)?></pre>
</div>
<?php endif; ?>

<?php if ($debug): ?>
<div id="debug">
	<dl class="log">
		<dt><label>duration: <?=round((double)$duration, 8)?> seconds</label></dt>
		<dd style="display: none;">
			<ul>
			<?php foreach ($events as $timestamp=>$event): ?>
				<li><?=$timestamp?> - <?=$event?></li>
			<?php endforeach; ?>
			</ul>
		</dd>
		<dt><label>events: <?=numberformat($event_count)?> // hooks: <?=numberformat($hook_count)?></label></dt>
		<dd style="display: none;">
		<?php foreach ($hooks as $hook_name=>$actual_hooks): ?>
			<div style="font-weight: bold;" onclick="$(this).next().toggle();"><?=$hook_name?></div>
			<ul style="display: none;">
			<?php foreach ($actual_hooks as $hook=>$data): ?>
				<li>
					<label onclick="$(this).next().toggle();"><?=$hook?></label>
					<ul style="display: none;">
					<?php foreach ($data as $item): ?>
						<li><?=$item?></li>
					<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
		</dd>
		<dt>memory: <?=numberformat(memory_get_peak_usage(true))?></dt>
		<dt><label>db queries: <?=numberformat(array_sum($query_count))?></label></dt>
		<dd style="display: none;">
			<ul>
				<li>SELECT: <?=numberformat($query_count['SELECT'])?></li>
				<li>UPDATE: <?=numberformat($query_count['UPDATE'])?></li>
				<li>DELETE: <?=numberformat($query_count['DELETE'])?></li>
			</ul>
		</dd>
	<?php foreach ($queries as $scope=>$scope_queries): ?>
		<dt class="sub">
			<label>
				table "<?=$scope?>"
				[<?=numberformat(count($scope_queries))?>/<?=numberformat(array_key_sum($scope_queries, 'rows'))?>/<?=numberformat(array_key_sum($scope_queries, 'duration'))?>]
			</label>
		</dt>
		<dd class="sub" style="display: none;">
			<ol class="zebra">
			<?php foreach ($scope_queries as $query): ?>
				<li>
					[<?=numberformat($query['rows'], 0, ',', '.')?>/<?=numberformat($query['duration'], 6, ',', '.')?>]
				<?php if (stripos($query['query'], 'SELECT')===0): ?>
					<a class="ajax toggle" href="<?=FrontController::GetLink('Debug', 'Debug', 'Explain', array('scope'=>$scope, 'query'=>$query['query']))?>">
						<code class="mysql"><?=htmlentities(trim($query['query']))?></code>
					</a>
				<?php else: ?>
					<code class="mysql"><?=htmlentities(trim($query['query']))?></code>
				<?php endif; ?>
				</li>
			<?php endforeach; ?>
			</ol>
		</dd>
	<?php endforeach; ?>
	</dl>
</div>
<?php endif; ?>