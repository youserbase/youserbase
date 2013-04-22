<table style="width: 100%;">
	<colgroup>
		<col width="50%"/>
		<col width="50%"/>
	</colgroup>
	<thead>
		<tr>
			<th><phrase id="UNTRANSLATED"/></th>
			<th><phrase id="TRANSLATED"/></th>
		</tr>
	</thead>
	<tbody style="vertical-align: top;">
	<?php $i=0; while (!empty($phrases['translated']) and !empty($phrases['translated'])): ?>
		<tr class="r<?=$i?> a<?=$i++%2?>">
			<td class="r0 a0">
			<?php if ($phrase_id = array_shift($phrases['untranslated'])): ?>
				<phrase id="<?=$phrase_id?>"/>
			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			</td>
			<td class="r1 a1">
			<?php if ($phrase_id = array_shift($phrases['translated'])): ?>
				<phrase id="<?=$phrase_id?>"/>
			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" current_page="<?=$skip/$limit?>" max="<?=$limit?>" link_class="lightbox" total="<?=$total?>"/>
