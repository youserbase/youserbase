
<youser id="<?=$object_id?>" type="avatar"/>
<youser id="<?=$object_id?>"/> <phrase id="ACTIVITY_COMMENT_WRITTEN"/> <a href="<?=FrontController::GetLink('datasheets', 'Compare', 'Index', array('compare_id' => $subject_id, 'tab' => 'Comments'));?>"><phrase id="COMPARISON"/> <phrase id="BETWEEN"/></a>

<?php
$device_id_ints = DBManager::Get('devices')->query("SELECT devices FROM compares WHERE compare_id = ?;", $subject_id)->fetch_item();
		$device_id_ints = explode('_', $device_id_ints);
		foreach ($device_id_ints as $id){
			$device_ids[] = DBManager::Get('devices')->query("SELECT device_id FROM device WHERE device_id_int = ?;", $id)->fetch_item();
		}
?>

<div>
<?php foreach ($device_ids as $item): ?>
	<device id="<?=$item?>" type="avatar"/>
<?php endforeach; ?>
</div>