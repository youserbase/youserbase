<form class="onchange_submit" action="<?=FrontController::GetLink('ChangeNavigation')?>" method="post" style="position: absolute; font-weight: bold; color: #fff; left: 5px;">
	<label for="navigation_id"> <phrase id="NAVIGATION"/>:</label>

	<select id="navigation_id" name="navigation_id">
	<?php foreach ($navigation_ids as $navigation_id): ?>
		<option <?=$navigation_id==$current_navigation_id?'selected="selected"':''?>><?=$navigation_id?></option>
	<?php endforeach; ?>
	</select>

	<button type="submit"> <span> <phrase id="CHANGE"/> </span> </button>
</form>

<form id="navigation_change_form" action="<?=FrontController::GetLink()?>" method="post">
<table style="width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="2" class="zebra">
	<thead>
		<tr>
			<th colspan="2" class="blank">
			</th>
			<th colspan="<?=count($roles)?>"><phrase id="VISIBILITY"/></th>
			<th class="blank">&nbsp;</th>
		</tr>
	</thead>
<?php foreach ($current_navigation as $index=>$section): ?>
	<tbody>
		<tr class="header">
			<th>
				<input type="text" name="section[<?=$section['name']?>][name]" value="<?=$section['name']?>" style="width: 100px;"/>
			</th>
			<th><phrase id="<?=$section['name']?>"/></th>
		<?php foreach ($roles as $role): ?>
			<th style="text-align: center;" class="highlight_column">
				<img src="<?=Helper::GetIconForRole($role)?>" alt="<?=strtoupper($role{0})?>" title="<?=$role?>"/>
			</th>
		<?php endforeach; ?>
			<th style="text-align: right;">
			<?php if ($index>0): ?>
				<a href="<?=FrontController::GetLink('MoveSection', array('direction'=>'up', 'section_id'=>$section['name']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_up.png')?>" alt="UP"/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
			<?php if ($index<count($current_navigation)-1): ?>
				<a href="<?=FrontController::GetLink('MoveSection', array('direction'=>'down', 'section_id'=>$section['name']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_down.png')?>" alt="DOWN"/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
				<a href="<?=FrontController::GetLink('RemoveSection', array('section_id'=>$section['name']))?>" class="confirm" title_phrase="DELETESECTION">
					<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt="DELETE"/>
				</a>
			</th>
		</tr>
	<?php foreach ($section['data'] as $inner_index=>$item): ?>
		<tr>
			<td class="bordered right">
				<input type="text" name="section[<?=$section['name']?>][items][<?=$item['unique_id']?>][title]" value="<?=$item['title']?>" style="width: 100px;"/>
			</td>
			<td class="bordered right">
				<select name="section[<?=$section['name']?>][items][<?=$item['unique_id']?>][link]" class="select_or_input" style="width: 160px;">
					<optgroup label_phrase="ACTIONS">
					<?php foreach ($actions as $action): ?>
						<option <?=$action==$item['link_original']?'selected="selected"':''?>><?=$action?></option>
					<?php endforeach; ?>
					</optgroup>
				<?php if (!empty($plugins)): ?>
					<optgroup label_phrase="PLUGINS">
					<?php foreach ($plugins as $plugin): ?>
						<option <?=$plugin==$item['link_original']?'selected="selected"':''?>><?=$plugin?></option>
					<?php endforeach; ?>
					</optgroup>
				<?php endif; ?>
					<optgroup label_phrase="OTHER">
						<option value="-1" <?=(!in_array($item['link_original'], array_merge($actions, $plugins)) and $item['type']!='page')?'selected="selected"':''?>><phrase id="EXTERNALLINK" quiet="true"/></option>
						<option value="-2" <?=$item['type']=='page'?'selected="selected"':''?>><phrase id="SYSTEM_PAGE" quiet="true"/></option>
					</optgroup>
				</select>
				<input type="text" name="section[<?=$section['name']?>][items][<?=$item['unique_id']?>][external]" value="<?=!in_array($item['link_original'], array_merge($actions, $plugins))?$item['link_original']:''?>"/>
			</td>
		<?php foreach ($roles as $role): ?>
			<td class="highlight_column">
				<input type="checkbox" name="section[<?=$section['name']?>][items][<?=$item['unique_id']?>][roles][]" value="<?=$role?>" <?=in_array($role, $item['roles'])?'checked="checked"':''?>/>
			</td>
		<?php endforeach; ?>
			</td>
			<td style="text-align: right;">
			<?php if ($index>0): ?>
				<a href="<?=FrontController::GetLink('MoveItemToSection', array('section_id'=>$section['name'], 'item_id'=>$item['unique_id'], 'new_section_id'=>$current_navigation[$index-1]['name']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_join.png')?>" alt=""/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
			<?php if ($index+1<count($current_navigation)): ?>
				<a href="<?=FrontController::GetLink('MoveItemToSection', array('section_id'=>$section['name'], 'item_id'=>$item['unique_id'], 'new_section_id'=>$current_navigation[$index+1]['name']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_join_down.png')?>" alt=""/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
			<?php if ($inner_index>0): ?>
				<a href="<?=FrontController::GetLink('MoveItem', array('direction'=>'up', 'section_id'=>$section['name'], 'item_id'=>$item['unique_id']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_up.png')?>" alt_phrase="UP"/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
			<?php if ($inner_index<count($section['data'])-1): ?>
				<a href="<?=FrontController::GetLink('MoveItem', array('direction'=>'down', 'section_id'=>$section['name'], 'item_id'=>$item['unique_id']))?>">
					<img src="<?=Assets::Image('famfamfam/arrow_down.png')?>" alt_phrase="DOWN"/>
				</a>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/blank.png')?>" alt=""/>
			<?php endif; ?>
				<a href="<?=FrontController::GetLink('RemoveItem', array('section_id'=>$section['name'], 'item_id'=>$item['unique_id']))?>" class="confirm" title_phrase="DELETEITEM">
					<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
<?php endforeach; ?>
	<tfoot>
		<tr style="text-align: right;">
			<td colspan="<?=(count($roles)+4)?>">
				<button type="submit"> <span> <phrase id="SAVE"/> </span> </button>
			</td>
		</tr>
	</tfoot>
</table>
</form>
