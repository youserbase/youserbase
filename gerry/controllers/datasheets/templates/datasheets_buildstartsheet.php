<div class="rbox">
<h3><phrase id="CREATE_A_NEW_DEVICE"/></h3>
	<?if (isset($initialForm)):?>
	<form class="validate" id="initForm" action="<?=FrontController::GetLink('savedevice')?>" method="GET">
		<dl>
		<dt/>
		<dd>
			<input type="hidden" name="initial" value="set"/>
		</dd>
			<dt>
				<label for='manufacturer_name'><phrase id="MANUFACTURER"/></label>
			</dt>
			<dd>
				<?=$initialForm['manufacturer_name'] ?>
			</dd>
			<dt>
				<label for="device_name"><phrase id="DEVICE_NAME"/></label>
			</dt>
			<dd>
				<input id="device_name" class="required" type="text" name="device_name" value="<?php if(isset($device_name)):?><?=$device_name?><?php endif;?>"/>
				<?php if(isset($device_name)):?>
					<div class="error">
						<phrase id="DEVICE_EXISTS"/>
					</div>
				<?php endif;?>
			</dd>
			<dt>
				<label for="main_type"><phrase id="MAIN_TYPE"/></label>
			</dt>
			<dd>
				<?=$initialForm['main_type']?>
			</dd>
			<dt/>
			<dd>
				<input type="submit" value_phrase="NEWENTRY"/>
			</dd>
		</dl>
	</form>
	<?endif?>
</div>