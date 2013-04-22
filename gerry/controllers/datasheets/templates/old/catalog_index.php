<?php if(isset($device_types) && isset($device_types_count)):?>
	<div id="device_types">
		<h3><phrase id="DEVICE_TYPES"/></h3>
		<?php foreach($device_types as $device_type):?>
			<?php if (!empty($device_types_count[$device_type['device_type_name']])):?>
				<a href="<?=FrontController::GetLink('Catalogue', array('device_type' => $device_type['device_type_name'], 'catalogue' => 'manufacturer_by_device_types'))?>">

					<phrase id="<?=$device_type['device_type_name']?>"/> (<?=$device_types_count[$device_type['device_type_name']]?>)

				</a>
			<?php endif;?>
		<?php endforeach;?>
	</div>
<?php endif;?>

<?php if(isset($manufacturers) && isset($manufacturers_count)):?>
	<div class="manufacturers">
		<h3><phrase id="MANUFACTURERS"/></h3>
		<?php foreach($manufacturers as $manufacturer):?>
			<?php if (!empty($manufacturers_count[$manufacturer['manufacturer_name']])):?>
				<a href="<?=FrontController::GetLink('catalogue', array('manufacturer_id' => $manufacturer['manufacturer_id'], 'catalogue' => 'device_types_by_manufacturer'))?>">

					<phrase id="<?=$manufacturer['manufacturer_name']?>"/> (<?=$manufacturers_count[$manufacturer['manufacturer_name']]?>)

				</a>
			<?php endif;?>
		<?php endforeach;?>
	</div>
<?php endif;?>

<?php if(isset($devices_by_manufacturer)):?>
		<div class="devices">
		<?php foreach($devices_by_manufacturer as $device):?>
		<a href="<?=FrontController::GetLink('Datasheets', 'datasheet', array('manufacturer_name' => $manufacturer_name, 'main_type' => $device_type, 'device_name' => $device['device_name']))?>">
			<phrase id="<?=$device['device_name']?>"/>
		</a>
		<?php endforeach;?>
	</div>
<?php elseif(isset($manufacturers_by_device_type)):?>
	<div class="manufacturers">
		<?php foreach($manufacturers_by_device_type as $manufacturer):?>
			<a href="<?=FrontController::GetLink('catalogue', array('catalogue' => 'devices_by_device_type_and_manufacturer', 'manufacturer_id' => $manufacturer['manufacturer_id'], 'device_type' => $device_type, 'manufacturer_name' => $manufacturer['manufacturer_name']))?>" ><phrase id="<?=$manufacturer['manufacturer_name']?>"/>
				<?php if($count[$manufacturer['manufacturer_name']] > 0): ?>
					(<?=$count[$manufacturer['manufacturer_name']]?>)
				<?php endif;?>
			</a>
		<?php endforeach;?>
	</div>
<?php elseif(isset($devices_by_device_type_and_manufacturer)):?>
	<div class="devices">
		<form method="POST" action="<?=FrontController::GetLink('sheetcomparison', 'compare')?>">
			<?php foreach($devices_by_device_type_and_manufacturer as $device):?>
				<input type="checkbox" name="<?=$device['device_name']?>" value="<?=$device['device_id']?>">
					<device id="<?=$device['device_id']?>" no_manufacturer="true"/>

			<?php endforeach;?>
			<input type="submit" value_phrase="COMPARE"/>
		</form>
	</div>
<?php elseif(isset($device_types_by_manufacturer)):?>
	<div class="devices">
		<?php foreach($device_types_by_manufacturer as $device_type):?>
			<a href="<?=FrontController::GetLink('catalogue', array('catalogue' => 'devices_by_device_type_and_manufacturer', 'manufacturer_id' => $manufacturer_id, 'device_type' => $device_type['device_type_name']))?>" >
				<phrase id=<?=$device_type['device_type_name']?>/>
				<?php if($count[$device_type['device_type_name']] > 0): ?>
					(<?=$count[$device_type['device_type_name']]?>)
				<?php endif;?>
			</a>
		<?php endforeach;?>
	</div>
<?php endif;?>



