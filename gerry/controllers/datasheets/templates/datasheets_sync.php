<div class="rbox">
	<form method="POST" action="<?=FrontController::GetLink('sync', array('target_device_id' => $device_id))?>">
		<ul>
			<li>
				<phrase id="SELECT_MANUFACTURER"/>
				<select name="manufacturer_id" class="sync_manufacturer_id">
				<option value=""></option>
					<?php foreach ($manufacturers as $manufacturer_id => $manufacturer_name):?>
						<option value="<?=$manufacturer_id?>">
							<phrase id="<?=$manufacturer_name?>"/>
						</option>
					<?php endforeach;?>
				</select>
			</li>
			<li class="devices_list">
				<phrase id="PLEASE_SELECT_A_MANUFACTURER"/>
			</li>
			<li>
				<phrase id="RELATIONSHIP"/>
				<select name="relationship">
					<option value="1"><phrase id="ANCESTOR" quiet="true"/></option>
					<option value="2"><phrase id="PREDECESSOR" quiet="true"/></option>
					<option value="3"><phrase id="SIBLING" quiet="true"/></option>
				</select>
			</li>
			<li>
				<button><span><phrase id="SUBMIT" quiet="true"/></span></button>
			</li>
		</ul>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
$('.sync_manufacturer_id').live('change', function () {
	$('.devices_list')
		.load('<?=FrontController::GetLink('sync_devices')?>', {
			'manufacturer_id' : $(this).val()
		});
});
//]]>
</script>