<?php if ($needle): ?>
<h1><phrase id="SEARCHRESULTS" needle="<?=$needle?>"/></h1>
<?php else: ?>
<h1><phrase id="SEARCH_HEADER"/></h1>
<?php endif; ?>
<form action="<?=FrontController::GetLink('Search')?>" method="post">
<div>
	<label for="needle"><phrase id="SEARCH_NEEDLE"/></label></dt>
	<input type="text" id="needle" name="needle" value="<?=$needle?>"/>
	<input type="submit" value_phrase="SEARCH"/>
</div>
</form>
<?php if (empty($quantities)): ?>
<p><phrase id="SEARCH_NORESULT"/></p>
<?php else: ?>
<h2><phrase id="SEARCH_RESULTS" quantity="<?=numberformat($quantities[0])?>"/></h2>

<div class="flora">
	<ul class="tabify" rel="type">
		<li>
			<a href="<?=FrontController::GetLink('Results', array('needle'=>$needle, 'type'=>''))?>" title="All">
				<span>
					<phrase id="SEARCH_RESULTS_ALL"/>
					(<?=numberformat($quantities[0])?>)
				</span>
			</a>
		</li>
<?php if (count($quantities)>2): ?>
	<?php foreach ($quantities as $type=>$quantity): ?>
	<?php if (empty($type)) continue; ?>
		<li>
			<a href="<?=FrontController::GetLink('Results', array('needle'=>$needle, 'type'=>$type))?>" title="<?=$type?>">
				<span>
					<phrase id="SEARCH_RESULTS_<?=strtoupper($type)?>"/>
					(<?=numberformat($quantity)?>)
				</span>
			</a>
		</li>
	<?php endforeach; ?>
<?php endif; ?>
	</ul>
</div>

<?php endif; ?>


<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#needle').focus();
});
//]]>
</script>
