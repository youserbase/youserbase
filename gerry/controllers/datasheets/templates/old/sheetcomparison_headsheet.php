<div class="comp_device_head">
	<div class="device">
		<h3>
			<a href="<?=FrontController::GetLink('datasheets', 'datasheets', 'page', array('device_id' => $device_id))?>">
				<phrase id="<?=strtoupper($manufacturer_name)?>"/> <phrase id="<?=strtoupper($device_name)?>"/>
			</a>
		</h3>
	</div>
</div>
<div class="comp_device_ratings">
	<?php if (isset($device_rating) or isset($tab_rating)): ?>
		<dl class="ratings zebra">
			<?=isset($device_rating)?$this->render_partial('rating', array('name'=>'device_rating', 'rating'=>$device_rating)):''?>
			<?php foreach ((array)$tab_rating as $tab => $rating):?>
				<?=$this->render_partial('rating', array('name'=>$tab, 'rating'=>$rating))?>
			<?php endforeach;?>
		</dl>
	<?php endif;?>
</div>
<div class="comp_picture_gallery">
	<?php if (!empty($pictures)): ?>
		<div class="device_sliderGallery">
			<?php if(is_array($pictures)):?>
				<ul class="items">
					<?php foreach ($pictures as $picture):?>
						<li><a href="<?=str_replace('thumb', 'large', $picture)?>" class="lightbox" rel="device_pictures"/><img src="<?=$picture?>"/></a></li>
					<?php endforeach;?>
				</ul>
			<?php endif;?>
		</div>
	<?php endif; ?>
</div>
