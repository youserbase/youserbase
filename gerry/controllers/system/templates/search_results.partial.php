<?php if($result['total'] > 0):?>
	<?php if($result['total'] > $limit):?>
		<pagination href="<?=FrontController::GetLink('Search', 'Search?needle='.$needle.'&page=')?>" current_page="<?=$skip/$limit?>" total="<?=$result['total']?>" max="<?=$limit?>"/>
	<?php endif;?>
	<li class="devices">
	<?php if(!empty($result['matches'])):?>
		<b>
			<phrase id="DEVICES"/>
		</b>
		<ul class="devices">
		<?php foreach ($result['matches'] as $id => $content):?>
			<li style="padding: 5px; float: left; width: 40%; height:100px; text-align: center;">
				<device id="<?=$content['device_id']?>" type="avatar"/><br/>
				<device id="<?=$content['device_id']?>"/>
			</li>
		<?php endforeach;?>
		</ul>
	</li>
	<li class="manufacturers">
		<b><phrase id="MANUFACTURERS"/></b>
		<?php if(isset($manu)):?>
		<ul class="manufacturers">
			<?php foreach ($manu as $id):?>
			<li style="float: left; width: 40%; height:100px; text-align: center;">
				<manufacturer id="<?=$id?>" image="small"/><br>
			</li>
			<?php endforeach;?>
		</ul>
		<?php endif;?>
	<?php endif;?>
	</li>
<?php endif;?>
	
	