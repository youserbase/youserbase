<?php if (isset($parameters[0]) and in_array($parameters[0], array('text', 'string'))): ?>
<div class="type-text">
<?php elseif (isset($parameters[0], $parameters[1]) and $parameters[0]=='range' and is_array($parameters[1]) and count($parameters[1])>=2): ?>
<div class="type-select">
<?php else: // Ich weiss, es müsste "type-check" sein, aber mit "type-text" sieht's nunmal richtig aus ;) ?>
<div class="type-text">
<?php endif; ?>
	<label for="item_<?=$section?>_<?=$key?>" title="<?=$section?> : <?=$key?>" class="tipsify east">
		<phrase id="CONFIG_<?=strtoupper($section)?>_<?=strtoupper($key)?>"/>
	</label>
<?php if (isset($parameters[0]) and $parameters[0]=='text'): ?>
	<textarea id="item_<?=$section?>_<?=$key?>" name="configuration[<?=$section?>][<?=$key?>]" class="autogrow" style="width: 95%;" rows="30" cols="40"><?=Config::Get($section, $key)?></textarea>
<?php elseif (isset($parameters[0]) and $parameters[0]=='string'): ?>
	<input type="text" id="item_<?=$section?>_<?=$key?>" name="configuration[<?=$section?>][<?=$key?>]" value="<?=Config::Get($section, $key)?>" <?=(isset($parameters[1]) and $parameters[1]=='required')?'class="required"':''?>/>
<?php elseif (isset($parameters[0], $parameters[1]) and $parameters[0]=='range' and is_array($parameters[1]) and count($parameters[1])>=2): ?>
	<select id="item_<?=$section?>_<?=$key?>" name="configuration[<?=$section?>][<?=$key?>]">
	<?php for ($i=$parameters[1][0]; $i<=$parameters[1][1]; $i+=(isset($parameters[1][2])?$parameters[1][2]:1)): ?>
		<option <?=$i==Config::Get($section, $key)?'selected="selected"':''?>><?=$i?></option>
	<?php endfor; ?>
	</select>
<?php else: ?>
	<input type="hidden" name="configuration[<?=$section?>][<?=$key?>]" value="0"/>
	<input type="checkbox" id="item_<?=$section?>_<?=$key?>" name="configuration[<?=$section?>][<?=$key?>]" value="1" <?=Config::Get($section, $key)?'checked="checked"':''?>/>
<?php endif; ?>
</div>
