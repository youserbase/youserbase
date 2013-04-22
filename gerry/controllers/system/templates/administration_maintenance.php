<h1><phrase id="MAINTENANCE"/></h1>
<ul>
    <li>
        <a href="<?=FrontController::GetLink('ClearCache')?>"><phrase id="MAINTENANCE_CLEARCACHE"/></a>(
        <?= number_format($cache_count, 0, ',', '.') ?>
        )
    </li>
    <li>
        <a href="<?=FrontController::GetLink('ClearSessions')?>"><phrase id="MAINTENANCE_CLEARSESSIONS"/></a>
    </li>
    <li>
        <a href="<?=FrontController::GetLink('ClearStatistics')?>"><phrase id="MAINTENANCE_CLEARSTATISTICS"/></a>
    </li>
    <li>
        <a href="<?=FrontController::GetLink('ProcessPictures')?>"><phrase id="MAINTENANCE_PROCESSPICTURES"/></a>
    </li>
    <?php
    if (Youser::May('delete_devices')):
    ?>
    <li>
        <a href="<?=FrontController::GetLink('WipeDeviceImages')?>" class="confirm" title="Wirklich?"><phrase id="MAINTENANCE_WIPEDEVICE_IMAGES"/></a>
    </li>
    <?php
    endif;
    ?>
</ul>