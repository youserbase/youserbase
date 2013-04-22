<ul id="dropbox_devices">
	<li style="display: none;">empty placeholder</li>
<?php foreach (Dropbox::Get() as $id): ?>
	<li>
		<input type="checkbox" name="compare[]" value="<?=$id?>" checked="checked" class="dropbox-device"/>
		<device id="<?=$id?>"/>
	</li>
<?php endforeach; ?>
</ul>
