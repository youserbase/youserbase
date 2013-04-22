<?php if(isset($sheet)):?>
	<div id="data_sheet">
		<ul class="tabify">
			<?php foreach($sheet as $page => $pagedata):?>
				<li>
					<a title="<?=$page?>" href="<?=FrontController::GetLink('phonesheet', array('main_type' => $main_type, 'manufacturer_name' => $manufacturer_name, 'device_name' => $device_name, 'device_id' => $device_id, 'tab' => $page))?>"><phrase id="<?=strtoupper($page)?>"/></a>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>