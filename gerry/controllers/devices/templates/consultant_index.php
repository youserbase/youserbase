<div id="col2" class="rbox">
	<div id="consultant">
		<h1><phrase id="CONSULTANT_HEADER"/></h1>
		<form action="<?=FrontController::GetLink()?>" method="get">
			<div id="consultant_manufacture_box">
				<div class="header">
					<phrase id="CONSULTANT_OPTION_MANUFACTURER"/>
				</div>
				<div class="floatbox">
					<select name="manufacturer_id" rel="m">
						<option value="-1" <?=!isset($_REQUEST['manufacturer_id'])?'selected="selected"':''?>><phrase id="CONSULTANT_ALL_MANUFACTURERS" quiet="true"/></option>
					<?php foreach ($manufacturers as $id=>$name): ?>
						<option value="<?=$id?>" <?=(isset($_REQUEST['manufacturer_id']) and $_REQUEST['manufacturer_id'] == $id)?'selected="selected"':''?>><phrase id="<?=$name?>"  quiet="true"/></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div id="consultant_shape_box">
				<div class="header">
					<phrase id="CONSULTANT_OPTION_SHAPE"/>
				</div>
				<div class="floatbox">
					<ul class="shapes">
						<li class="r0">
							<input id="shape_all" type="radio" name="shape" rel="s" value="-1"
							<?= (! isset ($_REQUEST['shape']) or $_REQUEST['shape'] == '-1')?'checked="checked"':'' ?>
/>
							<label for="shape_all">
								<phrase id="CONSULTANT_ALL_SHAPES"/>
							</label>
						</li>
					<?php $i=1; foreach ($shapes as $shape): ?>
						<li class="r<?=$i++?>">
							<label for="<?=strtolower($shape)?>">
								<img src="<?=Assets::Image('big/big_mobile_%s.png', strtolower($shape))?>" alt_phrase="<?=$shape?>"/>
							</label>
							<br/>
							<input id="<?=strtolower($shape)?>" type="radio" name="shape" rel="s" value="<?=$shape?>"
							<?=(isset($_REQUEST['shape']) and $_REQUEST['shape'] == $shape)?'checked="checked"':''?>
/>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div id="consultant_inputmethod_box">
				<div class="header">
					<phrase id="CONSULTANT_OPTION_INPUTMETHOD"/>
				</div>
				<div class="floatbox">
					<ul class="inputmethods">
						<li class="r0">
							<input id="inputmethod_all" type="radio" name="input_method" rel="i" value="-1" <?=(!isset($_REQUEST['input_method']) or $_REQUEST['input_method'] == '-1')?'checked="checked"':''?>/>
							<label for="inputmethod_all">
								<phrase id="CONSULTANT_ALL_INPUTMETHODS"/>
							</label>
						</li>
					<?php $i=1; foreach ($input_methods as $input_method): if ($input_method=='QWERTY') continue; ?>
						<li class="<?=$i++?>">
							<label for="<?=strtolower($input_method)?>">
								<img src="<?=Assets::Image('big/big_mobile_input_%s.png', strtolower($input_method))?>" alt_phrase="INPUTMETHOD_<?=$input_method?>"/>
							</label>
							<br/>
							<input id="<?=strtolower($input_method)?>" type="radio" name="input_method" rel="i" value="<?=$input_method?>" <?=(isset($_REQUEST['input_method']) and $_REQUEST['input_method'] == $input_method)?'checked="checked"':''?>/>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div id="consultant_submit_box">
				<button type="submit">
					<span><phrase id="CONSULTANT_FILTER"/></span>
				</button>
			</div>
		</form>
	</div>
</div>
<div id="col3" class="rbox">
	<h1><phrase id="CONSULTANT_HEADER"/><phrase id="RESULTS"/></h1>
	<div id="consultant_k" style="position: relative;">
		<ul id="consultant_devices" class="device_list" rel="{size:<?=$max_devices?>}">
		<?php foreach ($devices as $device_id=>$device): ?>
			<?= $this->render_partial('consultant_device', compact('device')) ?>
		<?php endforeach; ?>
		</ul>
		<div id="consultant_loading" style="display: none; position: absolute; top: 0; right: 0; bottom: 0; left: 0; opacity: 0.8; background: #fff url(<?=Assets::Image('ajax_indicator_giant.gif')?>) center no-repeat;">
			&nbsp;
		</div>
	</div>
</div>
