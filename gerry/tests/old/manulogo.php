<?php
require '../../classes/Image.class.php';
require '../controllers/devices/classes/Manufacturer_Image.class.php';

$name = 'manufoobar';

if (!empty($_POST))
{
	$extension = implode(array_slice(explode('/', $_FILES['image']['type']), -1, 1));
	$filename = $name.'_logo.original.'.$extension;
	move_uploaded_file($_FILES['image']['tmp_name'], $filename);

	Manufacturer_Image::Process($name, $filename);
}
?>
<form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
	<input type="file" name="image"/>
	<input type="submit" name="foo" value="keks"/>
</form>
<hr/>
<ul>
<?php foreach (glob($name.'_logo.*') as $file): ?>
	<li>
		<img src="<?=$file?>"/>
	</li>
<?php endforeach; ?>
</ul>
