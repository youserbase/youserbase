<table id="consultant" cellpadding="0" cellspacing="0">
	<tbody class="r0">
		<tr class="r0">
			<td class="r0">
				<div class="rbox">
					<ul class="device_list" rel="{size:<?=$max_devices?>}">
					<?php foreach ($devices as $device_id=>$device): ?>
						<?=$this->render_partial('consultant_device', compact('device'))?>
					<?php endforeach; ?>
						<li class="spinner" style="display: none;">
							<img src="<?=Assets::Image('ajax_indicator_big.gif')?>" alt="Loading..."/>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</td>
			<td class="r1">
			<div class="rbox">
				<h1><phrase id="CONSULTANT_HEADER"/></h1>
				<div class="content">
				<form action="<?=FrontController::GetLink()?>" method="post">
					<dl>
						<dt><phrase id="CONSULTANT_OPTION_MANUFACTURER"/></dt>
						<dd>
							<select name="manufacturer_id" rel="m">
								<option value="-1" <?=!isset($_POST['manufacturer_id'])?'selected="selected"':''?>><phrase id="CONSULTANT_ALL_MANUFACTURERS"/></option>
							<?php foreach ($manufacturers as $id=>$name): ?>
								<option value="<?=$id?>" <?=(isset($_POST['manufacturer_id']) and $_POST['manufacturer_id']==$id)?'selected="selected"':''?>><phrase id="<?=$name?>"/></option>
							<?php endforeach; ?>
							</select>
						</dd>

						<dt><phrase id="CONSULTANT_OPTION_SHAPE"/></dt>
						<dd>
							<ul class="shapes">
								<li class="r0">
									<input id="shape_all" type="radio" name="shape" rel="s" value="-1" <?=(!isset($_POST['shape']) or $_POST['shape']=='-1')?'checked="checked"':''?>/>
									<label for="shape_all"><phrase id="CONSULTANT_ALL_SHAPES"/></label>
								</li>
							<?php $i=1; foreach ($shapes as $shape): ?>
								<li class="<?=$i++?>">
									<label for="<?=strtolower($shape)?>">
										<img src="<?=Assets::Image('consultant/%s.gif', strtolower($shape))?>" alt_phrase="<?=$shape?>"/>
									</label>
									<br/>
									<input id="<?=strtolower($shape)?>" type="radio" name="shape" rel="s" value="<?=$shape?>" <?=(isset($_POST['shape']) and $_POST['shape']==$shape)?'checked="checked"':''?>/>
								</li>
							<?php endforeach; ?>
							</ul>
<?php
/** OLD SELECTOR
							<select name="shape" rel="s">
								<option value="-1" <?=!isset($_POST['shape'])?'selected="selected"':''?>><phrase id="CONSULTANT_ALL_SHAPES"/></option>
							<?php foreach ($shapes as $shape): ?>
								<option value="<?=$shape?>" <?=(isset($_POST['shape']) and $_POST['shape']==$shape)?'selected="selected"':''?>><phrase id="<?=$shape?>"/></option>
							<?php endforeach; ?>
							</select>
**/
?>
						</dd>

						<dt><phrase id="CONSULTANT_OPTION_INPUTMETHOD"/></dt>
						<dd>
							<ul class="inputmethods">
								<li class="r0">
									<input id="inputmethod_all" type="radio" name="input_method" rel="i" value="-1" <?=(!isset($_POST['input_method']) or $_POST['input_method']=='-1')?'checked="checked"':''?>/>
									<label for="inputmethod_all"><phrase id="CONSULTANT_ALL_INPUTMETHODS"/></label>
								</li>
							<?php $i=1; foreach ($input_methods as $input_method): ?>
								<li class="<?=$i++?>">
									<label for="<?=strtolower($input_method)?>">
										<img src="<?=Assets::Image('consultant/%s.gif', strtolower($input_method))?>" alt_phrase="INPUTMETHOD_<?=$input_method?>"/>
									</label>
									<br/>
									<input id="<?=strtolower($input_method)?>" type="radio" name="input_method" rel="i" value="<?=$input_method?>" <?=(isset($_POST['input_method']) and $_POST['input_method']==$input_method)?'checked="checked"':''?>/>
								</li>
							<?php endforeach; ?>
							</ul>
<?php
/**
							<select name="input_method" rel="i">
								<option value="-1" <?=!isset($_POST['input_method'])?'selected="selected"':''?>><phrase id="CONSULTANT_ALL_INPUTMETHODS"/></option>
							<?php foreach ($input_methods as $input_method): ?>
								<option value="<?=$input_method?>" <?=(isset($_POST['input_method']) and $_POST['input_method']==$input_method)?'selected="selected"':''?>><phrase id="INPUTMETHOD_<?=$input_method?>"/></option>
							<?php endforeach; ?>
							</select>
**/
?>
						</dd>

						<dt>&nbsp;</dt>
						<dd>
							<button> <span><phrase id="CONSULTANT_FILTER"/></span> </button>
						</dd>
					</dl>
				</form>
				</div>
			</div>
			</td>
		</tr>
	</tbody>
</table>