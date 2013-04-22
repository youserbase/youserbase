<?php
	ob_start();
	include '../../includes/bootstrap.inc.php';

	if (!empty($_POST))
	{
		$path = explode('/', $_POST['path']);
		if (in_array($_POST['action'], array('Set', 'Stuff')))
		{
			array_push($path, $_POST['value']);
		}
		call_user_func_array(array('Sitzung', $_POST['action']), $path);
	}

	Sitzung::Prolong('+60');

	$data = Sitzung::Get();
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<fieldset>
	<legend>Session Data</legend>
	<dl>
		<dt>Path</dt>
		<dd>
			<input type="text" name="path"/>
		</dd>
		<dt>Value</dt>
		<dd>
			<input type="text" name="value"/>
		</dd>
		<dt>
			Aktion
		</dt>
		<dd>
			<input type="submit" name="action" value="Set"/>
			<input type="submit" name="action" value="Stuff"/>
			<input type="submit" name="action" value="Clear"/>
		</dd>
	</dl>
</fieldset>
</form>
<hr/>
<pre><?=print_r($_SERVER, true)?></pre>