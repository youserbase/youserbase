<?php foreach ($devices as $device_id=>$device): ?>
<?=$this->render_partial('consultant_device', compact('device'))?>
<?php endforeach; ?>