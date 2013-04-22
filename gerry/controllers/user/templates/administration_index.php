<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$youser_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('useradministration', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<table style="width: 100%;" cellspacing="0" cellpadding="2" class="zebra">
	<thead>
		<tr>
			<th><phrase id="NICKNAME"/></th>
			<th><phrase id="STATUS"/></th>
			<th>&nbsp;</th>
			<th><phrase id="EMAIL"/></th>
			<th><phrase id="ROLE"/></th>
			<th><phrase id="ROLES"/></th>
			<th><phrase id="REGISTERTIMESTAMP"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php for ($i=0, $youser=reset($yousers); $i<Config::Get('useradministration', 'pagination_count'); $i++, $youser=next($yousers)): ?>
		<tr class="r<?=$i?> a<?=$i%2?>">
		<?php if ($youser!==false): ?>
			<td><youser id="<?=$youser['youser_id']?>" force="true"/></td>
			<td>
			<?php if ($youser['blocked']=='yes'): ?>
				<img src="<?=Assets::Image('famfamfam/status_busy.png')?>" alt="Gesperrt"/>
			<?php elseif (!$youser['activated']): ?>
				<img src="<?=Assets::Image('famfamfam/status_away.png')?>" alt="Nicht aktiviert"/>
			<?php elseif ($youser['online']): ?>
				<img src="<?=Assets::Image('famfamfam/status_online.png')?>" alt="Online"/>
			<?php else: ?>
				<img src="<?=Assets::Image('famfamfam/status_offline.png')?>" alt="Aktiv"/>
			<?php endif; ?>
			</td>
			<td>
			<?php if ($youser['youser_id']!=Session::Get('Youser', 'id')): ?>
				<a class="lightbox" href="<?=FrontController::GetLink('User', 'Messages', 'Send', array('to'=>$youser['youser_id']))?>">
					<img src="<?=Assets::Image('famfamfam/email_edit.png')?>" alt_phrase="SENDMAIL"/>
				</a>
			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			</td>
			<td><?=$youser['email']?></td>
			<td>
				<img src="<?=Helper::GetIconForRole($youser['role'])?>" alt=""/>
				<?=$youser['role']?>
			</td>
			<td>
				<?=implode('<br/>', explode(',', $youser['roles']))?>
			</td>
			<td><?=date('d.m.Y H:i:s', $youser['register_timestamp'])?></td>
			<td>
				<a class="lightbox" href="<?=FrontController::GetLink('Edit', array('youser_id'=>$youser['youser_id']))?>">
					<img src="<?=Assets::Image('famfamfam/pencil.png')?>" alt_phrase="EDIT"/>
				</a>
				<a class="confirm" href="<?=FrontController::GetLink('Delete', array('youser_id'=>$youser['youser_id']))?>" title_phrase="DELETEUSER">
					<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
				</a>
			</td>
		<?php else: ?>
			<td colspan="8">&nbsp;</td>
		<?php endif; ?>
		</tr>
	<?php endfor; ?>
	</tbody>
</table>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$youser_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('useradministration', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>