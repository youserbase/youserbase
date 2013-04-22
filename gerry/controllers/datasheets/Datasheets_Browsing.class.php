<?php
class Datasheets_Browsing extends Controller
{
	private $limit = 10;

	public function browseSimilar()
	{
		if (!isset($_GET['device_id']))
		{
			return false;
		}
		$skip = 0;
		if(isset($_GET['skip']))
		{
			$skip = $_GET['skip'];
		}

		$similar_devices = investigator::getSimilarDevices($_GET['device_id'], $skip, $this->limit);

		if (empty($similar_devices))
		{
			return false;
		}

		$template = $this->get_template(true);
		$template->assign('similarity', $similar_devices);
		$template->assign('device_id', $_GET['device_id']);
		$template->assign('skip', $skip);
	}

	public function browseHistory()
	{
		$skip = 0;
		if(isset($_GET['skip']))
		{
			$skip = $_GET['skip'];
		}

		$device_ids = Session::Get('history', 'devices');
		if (empty($device_ids))
		{
			return false;
		}

		$device_ids = array_slice($device_ids, $skip, $this->limit);

		$template = $this->get_template(true);
		$template->assign('device_ids', $device_ids);
		$template->assign('skip', $skip);
	}

	public function browseBest()
	{
		$skip = 0;
		if(isset($_Get['skip']))
		{
			$skip = $_Get['skip'];
		}

		$best_devices = investigator::getBestDevices($skip, $this->limit);

		if (empty($best_devices))
		{
			return false;
		}

		$template = $this->get_template(true);
		$template->assign('device_ids', $best_devices);
		$template->assign('skip', $skip);
	}
}
?>
