<div class="device_head">
		<?php if(Youser::Is('god') || Youser::Is('root') || Youser::Is('administrator')):?>
		<img class="toggle_copy" alt="copy_device" title_phrase="COPY_DEVICE" src="<?=Assets::Image('famfamfam/page_copy.png')?>"/>
		<div class="copy_device">
			<form action="<?=FrontController::GetLink('initCopyDevice')?>" method="post">
				<dl>
					<input type="hidden" name="device_id" value="<?=$device_id?>"/>
					<input type="hidden" name="device_name" value="<?=$device_name?>"/>
					<input type="hidden" name="device_type" value="<?=$main_type?>"/>
					<input type="hidden" name="manufacturer_name" value="<?=$manufacturer_name?>"/>
					<dt>
						<label for="new_device_name">
							<phrase id="NEW_DEVICE_NAME"/>
						</label>
					</dt>
					<dd>
						<input type="text" name="new_device_name" size="20px"/>
					</dd>
					<dt>
						<label for="new_manufacturer">
							<phrase id="NEW_MANUFACTURER"/>
						</label>
					</dt>
					<dd>
						<select name="manufacturer_id">
							<?php foreach($manufacturers as $manufacturer):?>
								<option value="<?=$manufacturer['manufacturer_id']?>"
									<?php if($manufacturer_name == $manufacturer['manufacturer_name']): ?>
										selected="selected"
									<?php endif;?>
								>
									<phrase id="<?=$manufacturer['manufacturer_name']?>"/>
								</option>
							<?php endforeach;?>
						</select>
					</dd>
					<dt/>
					<dd>
						<input type="submit" value_phrase="COPY_DEVICE"/>
					</dd>
				</dl>
			</form>
		</div>
		<?php endif;?>
</div>

<?php if (isset($device_rating) or isset($tab_rating)): ?>
	<?=isset($device_rating)?$this->render_partial('rating', array('name'=>'device_rating', 'rating'=>$device_rating, 'count' => $count)):''?>
	<div class="tab_rating">
		<dl class="ratings zebra" >
			<?php foreach ((array)$tab_rating as $tab => $rating):?>
				<?=$this->render_partial('rating', array('name'=>$tab, 'rating'=>$rating))?>
			<?php endforeach;?>
		</dl>
	</div>
<?php endif;?>



<script>
$(function() {
	$(".toggle_ratings").click(function() {
		$(".tab_rating").toggle("slow");
		$(".toggle_ratings").toggle();
	});
	$(".copy_device").hide();

	$(".toggle_copy").click(function() {
		$(".copy_device").toggle("slow");
	});

	$(".upload_picture").hide();

	$(".toggle_upload").click(function() {
		$(".upload_picture").toggle("slow");
		$(".toggle_upload").toggle();
	});

	$(".picture").hide();

	$(".toggle_picture").click(function() {
		$(".picture").toggle("slow");
		$(".toggle_picture").toggle();
	});

	$(".add_upload").click(function () {
		$(".upload_form").append('<input type="file" class="added" name="picture_source_path[]"/>');
	});
});
</script>