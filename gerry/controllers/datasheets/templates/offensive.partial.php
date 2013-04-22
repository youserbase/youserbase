	<form class="ajax" method="POST" action="<?=FrontController::GetLink('OffensiveComment', array('device_id' => $device_id, 'comments_id' => $comments_id))?>">
		<table>
		<tr>
			<td>
			<?php if(Youser::Is('administrator', 'root', 'god')):?>
				<select name="offensive">
					<?=$offensive_counts > 0?'<option value="-1"><phrase id="NOT_OFFENSIVE" quiet="true"/></option>':''?>
					<?=$offensive_counts >= 1?'<option value="3"><phrase id="DELETE" quiet="true"/></option>':'<option value="1"><phrase id="OFFENSIVE" quiet="true"/></option>'?>
				</select>
			<?php else:?>
				<phrase id="ARE_YOU_SURE"/>?
			<?php endif;?>
			</td>
		</tr>
		<button><span><phrase id="REPORT" quiet="true"/></span></button>
		<button class="cancel"><span><phrase id="CANCEL" quiet="true"/></span></button>
		</table>
	</form>