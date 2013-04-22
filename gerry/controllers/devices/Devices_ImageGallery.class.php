<?php
class Devices_ImageGallery extends Controller
{
	const display_limit = 8;

	public function Index()
	{
		if (empty($_GET['device_id']))
		{
			throw new Exception('Device id missing');
		}

		$device = Device::Get($_GET['device_id']);
		$page = isset($_GET['page'])
			? $_GET['page']
			: (isset($_GET['id'])
				? floor(array_search($_GET['id'], $device['pictures']) % self::display_limit)
				: 0);

		$pictures = array();
		foreach ($device['pictures'] as $picture_id)
		{
			$pictures[ $picture_id ] = array(
				'thumb'=>DeviceHelper::GetImage($device['id'], 'avatar', $picture_id),
				'image'=>DeviceHelper::GetImage($device['id'], 'large', $picture_id),
				'original'=>DeviceHelper::GetImage($device['id'], 'original', $picture_id),
			);
		}
		$current_id = isset($_GET['picture_id'])
			? $_GET['picture_id']
			: (isset($_GET['picture_index'])
				? key(array_slice($pictures, $_GET['picture_index'], 1, true))
				: key($pictures)
			);
		$template = $this->get_template(true);
		$template->assign(compact('device', 'pictures', 'page'));
		$template->assign('max_pages', floor(count($pictures)/self::display_limit ));
		$template->assign('limit', self::display_limit);
		$template->assign('id', $current_id);
		$template->assign('manufacturer', BabelFish::Get($device['manufacturer']));
		$template->assign('model', BabelFish::Get($device['name']));
	}

	public function GetImage()
	{
		$device = Device::Get($_GET['device_id']);
		$this->get_template(false, DeviceHelper::GetImage($_GET['device_id'], 'large', $device['pictures'][ $_GET['image_id'] ]));
	}

	public function GetPicture()
	{
	    $device = Device::Get($_GET['device_id']);
	    $picture_id = $device['pictures'][ $_GET['index'] ];
	    $limit = isset($_GET['limit']) ? $_GET['limit'] : 1;

	    $start = $_GET['index'] - $_GET['index'] % $limit;
	    $end = min($start + $limit, count($device['pictures']));

	    $pictures = array();
	    for ($i=$start; $i<$end; $i++)
	    {
	        $pictures[$i] = DeviceHelper::GetImage($device['id'], $_GET['size'], $device['pictures'][$i]);
	    }

	    $this->get_template(false, $limit == 1
	        ? reset($pictures)
	        : json_encode($pictures)
	    );
	}

	public function GetImages()
	{
		$device = Device::Get($_GET['device_id']);

		$images = array();
		foreach ($device['pictures'] as $picture_id)
		{
			$images[ $picture_id ] = array(
				'thumb'=>DeviceHelper::GetImage($device['id'], 'avatar', $picture_id),
				'image'=>DeviceHelper::GetImage($device['id'], 'large', $picture_id),
			);
		}

		$this->get_template(false, json_encode($images));
	}

	public function AddImage_POST()
	{
		if (empty($_REQUEST['device_id']) or empty($_FILES['picture_source_path']['name']))
		{
			Dobber::ReportError('Kein Gerät oder keine Bilder angegeben');
			return;
		}

		DeviceHelper::InsertImage($_REQUEST['device_id'], 'picture_source_path', $_POST['picture_type']);

		FrontController::Relocate( DeviceHelper::GetLink($_REQUEST['device_id']) );
	}

	public function AddImage()
	{
		$template = $this->get_template(true);
		$template->assign('device_id', $_REQUEST['device_id']);
	}

	public function Cooliris()
	{
		$template = $this->get_template(true);
		$template->assign('device_id', $_REQUEST['device_id']);
		$template->assign('device_name', DeviceHelper::GetName($_REQUEST['device_id']));
		$template->assign('device_link', DeviceHelper::GetLink($_REQUEST['device_id']));
		$template->assign('images', DBManager::Get('devices')->query("SELECT device_pictures_id, original_filename FROM device_pictures WHERE device_id=? ORDER BY master_image='yes' DESC, original_filename ASC",
			$_REQUEST['device_id']
		)->to_array());

		Header('Content-Type: application/rss+xml');
		echo $template->render();
		die;
	}

	public function Reorder()
	{
		foreach ($_POST['order'] as $position => $picture_id)
		{
			DBManager::Get('devices')->query("UPDATE device_pictures SET position=? WHERE device_id=? AND device_pictures_id=?",
				$position,
				$_POST['device_id'],
				$picture_id
			);
		}
		Dobber::ReportSuccess('CHANGED_SAVED');

		$this->get_template(false, '');
	}

	public function Administration()
	{
		$device = Device::Get($_GET['device_id']);
		$pictures = array();
		foreach ($device['pictures_full'] as $picture)
		{
			if (!isset($pictures[$picture['type']]))
			{
				$pictures[$picture['type']] = array();
			}
			array_push($pictures[$picture['type']], $picture);
		}

		$types = DeviceHelper::GetPictureTypes();
		$types = $types['device_pictures_type'];

		$template = $this->get_template(true);
		$template->assign('pictures', $pictures);
		$template->assign('types', $types);
		$template->assign('device_id', $_REQUEST['device_id']);
	}

	public function Edit_POST()
	{
		$device = Device::Get($_POST['device_id']);
		$picture_id = $_POST['id'];

		if (!empty($_POST['delete']) and $_POST['delete']=='absolutely')
		{
			// Remove picture from db and filesystem
			DeviceHelper::RemoveImage($_POST['device_id'], $_POST['id']);
			// What happens if I delete the master image?
			if ($device['pictures_full'][$picture_id]['master_image']=='yes')
			{
				$new_id = array_shift($device['pictures']);
				while ($new_id !==null and $new_id == $picture_id)
				{
					$new_id = array_shift($device['pictures']);
				}
				if ($new_id)
				{
					DBManager::Get('devices')->query("UPDATE device_pictures SET master_image = 'yes' WHERE device_id=? AND device_pictures_id=?", $_POST['device_id'], $new_id);
				}
			}

			if ($GLOBALS['VIA_AJAX'])
			{
				Header('X-Refresh: true');
				Lightbox::Close();
			}
			else
			{
				FrontController::Relocate('Administration', array('device_id'=>$_POST['device_id']));
			}
			die;
		}

		if (!$device['pictures_full'][$picture_id]['master_image']=='yes')
		{
			DBManager::Get('devices')->query("UPDATE device_pictures SET master_image = 'no' WHERE device_id=?", $_POST['device_id']);
		}

		DBManager::Get('devices')->query("UPDATE device_pictures SET master_image = ?, device_pictures_type = ?, angle = ?, situation = ? WHERE device_id = ? AND device_pictures_id = ?",
			!empty($_POST['master_image']) ? 'yes' : 'no',
			$_POST['picture_type'],
			(!empty($_POST['angle']) and $_POST['angle']!=-1) ? $_POST['angle'] : null,
			(!empty($_POST['situation']) and $_POST['situation']!=-1) ? $_POST['situation'] : null,
			$_POST['device_id'],
			$_POST['id']
		);

		Device::Invalidate($_POST['device_id']);

		Dobber::ReportNotice('CHANGES_SUBMITTED');
		return false;
	}

	public function Edit()
	{
		$device = Device::Get($_REQUEST['device_id']);
		$picture_id = $_REQUEST['id'];
		$position = array_search($picture_id, $device['pictures']);
		$next = isset($device['pictures'][$position+1])
			? $device['pictures'][$position+1]
			: false;
		$previous = isset($device['pictures'][$position-1])
			? $device['pictures'][$position-1]
			: false;
		$picture = $device['pictures_full'][$picture_id];
		$types = DeviceHelper::GetPictureTypes();

		$template = $this->get_template(true);
		$template->assign(compact('types', 'picture', 'position', 'next', 'previous'));
		$template->assign('total', count($device['pictures_full']));
	}
}
?>