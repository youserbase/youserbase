<div class="rbox">
	<h3><phrase id="INVITE_YOUSERS"/></h3>
	<form method="POST" action="<?=FrontController::GetLink('Invite')?>">
	<table>
		<tr>
			<td>
				<phrase id="SENDER"/>
			</td>
			<td>
				<input type="text" name="youser" value="<?=$youser?>" disabled="disabled"/>
			</td>
		</tr>
		<tr>
			<td>
				<phrase id="RECEPIENTS_EMAIL"/> <img src="<?=Assets::Image('famfamfam/help.png')?>" alt_phrase="HELP" title_phrase="SEPARATE_MAILS_WITH_SPACES"/> 
			</td>
			<td>
				<textarea name="mail" class="recepients_email" cols="60" rows="2"><?php if(isset($mail)):?><?=$mail?><?php endif?></textarea>
				<?php if(isset($invalid)):?>
					<phrase id="INVALID_MAIL_ADRESS"/> <?=$invalid?>
				<?php endif?>
			</td>
		</tr>
		<tr>
			<td>
				<phrase id="YOUR_TEXT_OPTIONAL"/>
			</td>
			<td>
				<textarea name="youser_text" class="youser_text" cols="60" rows="3"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<phrase id="MESSAGE_FROM_YOUSERBASE"/>
			</td>
			<td>
				<textarea name="invitation" disabled="disabled" class="youserbase_text" cols="60" rows="6"><?=$mail_text?></textarea>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<button><span><phrase id="INVITE"/></span></button>
				<button class="cancel"><span><phrase id="CANCEL"/></span></button>
			</td>
		</tr>
	</table>
	</form>
</div>