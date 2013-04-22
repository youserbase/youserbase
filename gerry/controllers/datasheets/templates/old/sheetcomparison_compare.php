<?php
	$this->assign('page_title', 'COMPARE');
?>
<div class="compare_history flora">
	<ul class="tabify">
		<li>
			<a title="history" href="<?=FrontController::GetLink('devicesurfhistory', 'history')?>">
				<phrase id="HISTORY"/>
			</a>
		</li>
		<li>
			<a title="compare" href="<?=FrontController::GetLink('devicesurfhistory', 'compare')?>"
				<phrase id="COMPARE"/>
			</a>
		</li>
	</ul>
</div>
<table>
	<?php if(isset($datasheet)):?>
		<tr>
			<td>
				<?=$datasheet?>
			</td>
		</tr>
	<?php endif;?>
</table>
