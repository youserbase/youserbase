<?php if (isset($option[0]) and $option[0]=='text'): ?>
    <textarea name="configuration[<?=$section?>][<?=$key?>]" class="autogrow" style="width: 95%;"><?=$value?></textarea>
<?php elseif (isset($option[0]) and $option[0]=='string'): ?>
    <input type="text" name="configuration[<?=$section?>][<?=$key?>]" value="<?=$value?>" <?=(isset($option[1]) and $option[1]=='required')?'class="required"':''?>"/>
<?php elseif (isset($option[0]) and $option[0]=='range' and isset($option[1]) and is_array($option[1]) and count($option[1])>=2): ?>
    <select name="configuration[<?=$section?>][<?=$key?>]">
    <?php for ($i=$option[1][0]; $i<=$option[1][1]; $i+=(isset($option[1][2])?$option[1][2]:1)): ?>
        <option <?=$i==$value?' selected="selected"':''?>><?=$i?></option>
    <?php endfor; ?>
    </select>
<?php else: ?>
    <input type="hidden" name="configuration[<?=$section?>][<?=$key?>]" value="0"/>
    <input type="checkbox" name="configuration[<?=$section?>][<?=$key?>]" value="1" <?=$value?'checked="checked"':''?>/>
<?php endif; ?>