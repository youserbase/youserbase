<div class="rbox">
	<h3>
		<phrase id="<?=strtoupper($main_type)?>"/>
	</h3>
	<form class="validate" action="<?=FrontController::GetLink('copyDevice')?>" method="post">
	
		<input type="hidden" name="device_id" value="<?=$device_id?>"/>
		<input type="hidden" name="device_name" value="<?=is_array($model)?reset($model):$model?>"/>
		<input type="hidden" name="device_type" value="<?=$main_type?>"/>
	
		<div>
			<label for="new_device_name">
				<phrase id="NEW_DEVICE_NAME"/>
			</label>
			<input class="required" type="text" id="new_device_name" name="new_device_name" size="20px"/>
		</div>
		<div>
			<label for="new_manufacturer">
				<phrase id="NEW_MANUFACTURER"/>
			</label>
			<select id="new_manufacturer" name="manufacturer_id">
				<?php foreach($manufacturers as $manufacturer):?>
					<option value="<?=$manufacturer['manufacturer_id']?>"
						<?php if(isset($manufacturer_name) && $manufacturer_name == $manufacturer['manufacturer_name']): ?>
							selected="selected"
						<?php endif;?>
					>
						<phrase id="<?=$manufacturer['manufacturer_name']?>"/>
					</option>
				<?php endforeach;?>
			</select>
		</div>
		<div>
			<label for="relationship"><phrase id="RELATIONSHIP"/></label>
			<select id="relationship" name="relation">
				<option value="1">
					<phrase id="COPY_CHILD"/>
				</option>
				<option value="2">
					<phrase id="COPY_PARENT"/>
				</option>
				<option value="3">
					<phrase id="SIBLING"/>
				</option>
			</select>
		</div>
		<div class="buttons">
			<button>
				<span>
					<phrase id="SUBMIT" quiet="true"/>
				</span>
			</button>
			<button class="cancel">
				<span>
					<phrase id="CANCEL" quiet="true"/>
				</span>
			</button>
		</div>
	</form>
</div>