<?php
	$__tab_mapping = array(
		'Media' => array('Devices', 'Devices_Media', 'Video', compact($device_id)),
		'REVIEW' => array('datasheets', 'Datasheets_Reviews', 'Index', compact('device_id')),
	);
	$__omit_header = array('Media');

	$this->assign('optional_headers', '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.FrontController::GetLink('Export', 'Comments', array('type'=>'rss', 'device_id'=>$device_id)).'" />');
?>
<div class="phonesheet <?=$tab?>">
<?php if (!in_array($tab, $__omit_header)): ?>
	<device id="<?=$device_id?>" header tab="<?=$tab?>" />
<?php endif; ?>
	<table>
		<colgroup>
			<col width="255px"/>
			<col/>
		</colgroup>
	<?php if ($tab == 'Comments'):?>
		<tbody>
			<tr>
				<td colspan="2">
					<form class="ajax comments_search">
						<label for="needle"><phrase id="COMMENTS_SEARCH"/></label>
						<input id="needle" class="needle stored:device_id:<?=$device_id?>" type="text" name="needle"/>
					</form>
					<?=Controller::Render('datasheets', 'Datasheets_Comments', 'Index', compact('device_id'))?>
				</td>
			</tr>
		</tbody>
	<?php elseif (isset($__tab_mapping[$tab])): ?>
		<tbody>
			<tr>
				<td colspan="2">
					<?=call_user_func_array(array('Controller', 'Render'), $__tab_mapping[$tab])?>
				</td>
			</tr>
		</tbody>
	<?php else:?>
		<?php $tbodies = 0;?>
		<?php if(isset($sheet[$tab])):?>
			<?php foreach($sheet[$tab] as $table => $contents):?>
				<?=$this->render_partial('component_view', compact('contents', 'tbodies', 'tab', 'table', 'device_id', 'rating', 'build_in', 'skip', 'comment_count'))?>
				<?php $tbodies++?>
			<?php endforeach;?>
		<?php endif;?>
	<?php endif;?>
	</table>
	<page id="disclaimer"/>
</div>