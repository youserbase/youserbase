<?php
switch (@$_REQUEST['action'])
{
	case 'list_css':
		if (!count($files=glob('sprites/*.css')))
		{
			die;
		}
		echo '<select>';
		foreach ($files as $css)
		{
			echo '<option>'.basename($css, '.css').'</option>';
		}
		echo '</select>';
		break;
	case 'load_css':
		$content = file_get_contents('sprites/'.$_GET['css'].'.css');
		$matched = preg_match_all('/-sprite\.([^\s.]+)/', $content, $matches, PREG_SET_ORDER);

		$files = array();
		foreach ($matches as $match)
		{
			if ($match[1]!='icon')
			{
				array_push($files, $match[1]);
			}
		}

		echo json_encode(array_merge(array_unique($files)));
		break;
	case 'generate_sprites':
		$width = $_POST['margin']['left']+$_POST['width']+$_POST['margin']['right'];
		$height = $_POST['margin']['top']+$_POST['height']+$_POST['margin']['bottom'];
		$size = count($_POST['files']);
		printf('%u * %ux%u<br/>', $size, $width, $height);

		$filename = 'sprites/'.$_POST['name'];

		$offset = 0;
		$css  = <<<CSS
.{$_POST['name']}-sprite {
	background: transparent url({$_POST['path']}{$filename}.png) no-repeat 0 0;
}
.{$_POST['name']}-sprite.icon {
	height: {$height}px;
	width: {$width}px;
}
CSS;
		$canvas = imagecreatetruecolor($width, $size*$height);
		$bg = imagecolorallocatealpha($canvas, 255, 255, 255, 0);
		imagefilledrectangle($canvas, 0, 0, $width, ($size+1)*$height, $bg);
		foreach ($_POST['files'] as $index=>$file)
		{
			$id = preg_replace('/[^a-z]/', '-', preg_replace('/\.[^.]+$/', '', strtolower(basename($file))));
			$css .= "\n".<<<CSS
.{$_POST['name']}-sprite.{$id} {
	background-position: 0 -{$offset}px;
}
CSS;
			if ($_POST['margin']['top']>0 or $_POST['margin']['left']>0)
			{
				$clean_offset = $offset + $_POST['margin']['top'];
				$css .= "\n".<<<CSS
.{$_POST['name']}-sprite.{$id}.clean, .{$_POST['name']}-sprite.{$id}.icon  {
	background-position: {$_POST['margin']['left']}px -{$clean_offset}px;
}
CSS;
			}

			$file = preg_replace('/^http:\/\/[^\/]+\//', '', $file);
			$tile = imagecreatefromstring(file_get_contents($file));
			imagecopy($canvas, $tile, 0+$_POST['margin']['left'], $offset+$_POST['margin']['top'], 0, 0, $_POST['width'], $_POST['height']);
			imagedestroy($tile);

			$offset += $height;

		}

		imagepng($canvas, './'.$filename.'.png', 9);
		imagedestroy($canvas);

		file_put_contents('./'.$filename.'.css', $css);

		printf('Filesize: '.number_format(filesize('./'.$filename.'.png'))).' byte<br/>';
		echo '<img src="'.$filename.'.png" alt=""/>';

		break;
	default:
		echo 'Unknown action';
		break;
}
?>