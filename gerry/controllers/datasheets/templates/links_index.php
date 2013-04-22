<div class="black links">
	<h3><phrase id="LINKS_FOR"/> <device id="<?=$device_id?>"/></h3>
	<div class="link_header">
		<div class="link_stats">
			<?=empty($links_count)?'<phrase id="NO_LINKS_IN_YOUR_LANGUAGE"/>':'<phrase id="LINKS_IN_YOUR_LANGUAGE"/>:'.($links_count)?>
			<br/>
			<?=empty($links_ol)?'<phrase id="NO_LINKS_IN_OTHER_LANGUAGES"/>':'<phrase id="LINKS_IN_OTHER_LANGUAGES"/>:'?>
		</div>
		<div class="other_links">
			<ul>
				<?php foreach ($links_ol as $language => $link):?>
					<li>
						<?=$language?> <?=count($link);?>
					</li>
				<?php endforeach;?>
			</ul>
		</div>
		<div class="edit_link" id="new_link" >
			<img src="<?=Assets::Image('famfamfam/add.png')?>" alt_phrase="NEW_LINK"/> <phrase id="NEW_LINK"/>
		</div>
	</div>
	<div class="rbox green new_link" style="display:none;">
		<h3><phrase id="ENTER_NEW_LINK"/></h3>
		<?=$this->render_partial('link_form', array('page_type' => $page_type, 'content_type' => $content_type, 'device_id' => $device_id, 'languages' => $languages))?>
	</div>
	<div class="">
		<?php if(!empty($links)):?>
			<table class="links" border="">
				<?php foreach ($links as $link):?>
					<tr>
						<td colspan="2">
							<phrase id="BY"/> <youser id="<?=$link['youser_id']?>"/> <phrase id="PAGE_TYPE"/> <phrase id="<?=$link['page_type']?>"/> <phrase id="CONTENT_TYPE"/> <phrase id="<?=$link['content_type']?>"/>
						</td>
						<td>
						<?php if(Youser::Is('administrator', 'root', 'god') || Youser::Id() == $link['youser_id']):?>
							<img class="edit_link" id="<?=$link['device_links_id']?>" src="<?=Assets::Image('famfamfam/add.png')?>" 	alt_phrase="NEW_LINK"/> <label class="edit_link" id="<?=$link['device_links_id']?>"><phrase id="Edit_LINK"/></label>
							<?php if(Youser::Is('administrator', 'root', 'god')):?>
								</td>
								<td>
									<form class="ajax" method="POST" action="<?=FrontController::GetLink('Links', 'delete', array('device_links_id' => $link['device_links_id']))?>">
										<button><span><phrase id="DELETE" quiet="true"/></span></button>
									</form>
							<?php endif;?>
						<?php endif;?>
						</td>
					</tr>
					<tr>
						<td class="link" colspan="2">
							<a href="<?=$link['link']?>" target="_blank"><?=$link['title']?></a> (<?=$link['link']?>)
							<div class="rbox green <?=$link['device_links_id']?>" style="display:none;">
								<h3>
									<phrase id="EDIT_YOUR_LINK"/>
								</h3>
								<?=$this->render_partial('link_form', array('page_type' => $page_type, 'content_type' => $content_type, 'device_id' => $device_id, 'languages' => $languages, 'link' => $link))?>
							</div>
						</td>
						<td>
							<?=($helpfull[$link['device_links_id']][0]['SUM(helpfull)'] == NULL)?0:'+'.$helpfull[$link['device_links_id']][0]['SUM(helpfull)']?>
							<a href="<?=FrontController::GetLink('helpfull', array('device_id' => $device_id, 'device_links_id' => $link['device_links_id'],'pro' => 1, 'helpfull' => ($helpfull[$link['device_links_id']][0]['SUM(helpfull)'] == NULL)?0:$helpfull[$link['device_links_id']][0]['SUM(helpfull)']))?>">
								<img src="<?=Assets::Image('famfamfam/control_add_blue.png')?>" alt_phrase="helpfull"/><phrase id="HELPFULL"/>
							</a>
							<br/>
							<?=($helpfull[$link['device_links_id']][0]['SUM(not_helpfull)'] == NULL)?0:'-'.$helpfull[$link['device_links_id']][0]['SUM(not_helpfull)']?>
							<a href="<?=FrontController::GetLink('helpfull', array('device_id' => $device_id, 'device_links_id' => $link['device_links_id'], 'pro' => -1, 'not_helpfull' => ($helpfull[$link['device_links_id']][0]['SUM(not_helpfull)'] == NULL)?0:$helpfull[$link['device_links_id']][0]['SUM(not_helpfull)']))?>">
								<img src="<?=Assets::Image('famfamfam/control_remove_blue.png')?>" alt_phrase="not_helpfull"/> <phrase id="NOT_HELPFULL"/>
							</a>
						</td>
					</tr>
				<?php endforeach;?>
			</table>
		<?php endif;?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$('.new_link form.ajax').bind('submitted', function () {
	$(this)
		.closest('.comments_links')
		.parent()
		.load('<?=FrontController::GetLink('Comments', 'Index')?>', {
			'device_id' : '<?=$device_id?>'
	});
});
$('.links form.ajax').bind('submitted', function () {
	$(this)
		.closest('.comments_links')
		.parent()
		.load('<?=FrontController::GetLink('Comments', 'Index')?>', {
			'device_id' : '<?=$device_id?>'
	});
});
$('.links a').live('click', function () {
	$.post($(this).attr('href'));
	$(this)
		.closest('.comments_links')
		.parent()
		.load('<?=FrontController::GetLink('Comments', 'Index')?>', {
			'device_id' : '<?=$device_id?>'
	});
	return false;
});
//]]>
</script>