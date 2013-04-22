<div class="systempage rbox blue">
<h3>
	<phrase id="PLEASE_NOTE"/>
	<?php if (Youser::Is('root', 'god')):?>
		<img src="<?=Assets::Image('famfamfam/comments_add.png')?>" class="disclaimer"/>
	<?php endif;?>
</h3>
	<div class="disclaimer_show">
		<?php foreach($disclaimer as $id => $text):?> 
			<?=$text?>
		<?php endforeach;?>
	</div>
	<?php if (Youser::Is('root', 'god')):?>
		<div class="disclaimer_edit ajax" style="display:none">
			<form method="POST" action="<?=FrontController::GetLink('save_disclaimer', array('tab' => $tab, 'device_id' => $device_id))?>">
				<input type="hidden" name="disclaimer_id" value="
					<?php foreach($disclaimer as $id => $text):?> 
						<?=$id?>
					<?php endforeach;?>
				"/>
				<input type="text" name="disclaimer_text" value="
					<?php foreach($disclaimer as $id => $text):?> 
						<?=$text?>
					<?php endforeach;?>
					" size="80"/>
				<button><span><phrase id="SAVE"/></span></button>
				<button class="cancel"><span><phrase id="CANCEL"/></span></button>
			</form>
		</div>
	<?php endif;?>
</div>