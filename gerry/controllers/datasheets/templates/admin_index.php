<div class="rbox unconfirmed_devices">
    <h3><phrase id="UNCONFIRMED_DEVICES"/></h3>
    <?php if (isset($device_ids)): ?>
     
    <?= $this->render_partial('unconfirmed', array('device_ids'=>$device_ids, 'device_count'=>$device_count, 'skip_device'=>$skip_device))?>
     
    <?php else : ?>
     
    <div class="nothig_to_do">
        <phrase id="NO_UNCONFIRMED_DEVICES"/>
    </div>
    <?php endif; ?>
     
</div>
<?php if (isset($device_ids)): ?>
 
<?= $this->render_partial('unconfirmedcomponents', array('components_count'=>$components_count, 'device_components_ids'=>$device_components_ids, 'skip_components'=>$skip_components))?>
 
<?php else : ?>
 
<div class="rbox unconfirmed_components">
    <h3><phrase id="NO_UNCONFIRMED_DEVICES"/></h3>
</div>
<?php endif; ?>
 
<div class="rbox">
    <h3><phrase id="OPTIMIZE_TABLES"/></h3><a href="<?=FrontController::GetLink('copy_device_names')?>"><phrase id="OPTIMIZE"/></a>
</div>
<script type="text/javascript">
    //<![CDATA[
    $('.select_all').live('click', function(){
        var check = $(this).attr('id');
        $('.' + check).attr('checked', 'checked');
        $('.select_all.a' + check).toggle();
        $('.select_all.' + check).toggle();
        $('.deselect_all.' + check).toggle();
    });
    
    $('.deselect_all').live('click', function(){
        var check = $(this).attr('id');
        $('.' + check).attr('checked', '');
        $('.select_all.a' + check).toggle();
        $('.select_all.' + check).toggle();
        $('.deselect_all.' + check).toggle();
    });
    
    $('.expand').live('click', function(){
        var tog = $(this).attr('id');
        $('.' + tog).toggle();
    });
    //]]>
</script>
