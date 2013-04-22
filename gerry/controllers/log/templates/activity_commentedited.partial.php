<?php if(Youser::Is('administrator', 'root', 'god')):?>
<youser id="<?=$object_id?>" type="avatar"/>
<device id="<?=$subject_id?>" type="avatar"/>
<youser id="<?=$object_id?>"/> <phrase id="ACTIVITY_COMMENT_EDITED"/> <device id="<?=$subject_id?>"/>
<?php endif;?>