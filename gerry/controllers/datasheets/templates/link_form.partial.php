<form class="ajax reload_tab" method="POST" action="<?=FrontController::GetLink('datasheets', 'Links', 'new_link', array('device_id' => $device_id))?>">
	<table>
		<tr>
			<td>
				<phrase id="TITLE"/>
			</td>
			<td>
				<input type="text" name="title" value="<?=isset($link['title'])?$link['title']:'';?>">
			</td>
		</tr>
		<tr>
			<td>
				<phrase id="LINK"/>
			</td>
			<td>
				<input type="text" name="link" value="<?=isset($link['link'])?$link['link']:'';?>">
			</td>
		</tr>
		<tr>
			<td>
				<phrase id="PAGE_TYPE"/>
			</td>
			<td>
				<select name="page_type">
				<?php foreach ($page_type as $page):?>
					<option <?=(isset($link['page_type']) && $page == $link['page_type']?'selected="selected"':'')?>><phrase id="<?=$page?>" quiet="true"/></option>
				<?php endforeach;?>
			</select>
			</td>
		</tr>
		<tr>
			<td>
			<phrase id="CONTENT_TYPE"/>
			</td>
			<td>
			<select name="content_type">
				<?php foreach ($content_type as $content):?>
					<option <?=(isset($link['content_type']) && $content == $link['content_type']?'selected="selected"':'')?>><phrase id="<?=$content?>" quiet="true"/></option>
				<?php endforeach;?>
			</select>
			</td>
		</tr>
		<tr>
			<td>
			<phrase id="LANGUAGE"/>
			</td>
			<td>
			<select name="link_language">
				<?php foreach ($languages as $lang):?>
					<option value="<?=$lang?>"><phrase id="<?=$lang?>" quiet="true"/></option>
				<?php endforeach;?>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<?php if (isset($link['device_links_id'])):?>
				<input type="hidden" name="device_links_id" value="<?=$link['device_links_id']?>"/>
			<?php endif;?>
				<button><span><phrase id="SUBMIT"/></span></button>
				<button class="cancel"><span><phrase id="CANCEL"/></span></button>
			</td>
		</tr>
	</table>
</form>
