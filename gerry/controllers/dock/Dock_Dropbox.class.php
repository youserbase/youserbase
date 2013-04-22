<?php
class Dock_Dropbox extends Controller
{
	private function add_device($id)
	{
		if (Dropbox::Contains($id))
		{
			Dobber::ReportNotice('ERROR_DEVICE_ALREADY_MEMORIZED');
			return false;
		}
		Dropbox::Add($id);
		Dobber::ReportNotice('SUCCESS_DEVICE_MEMORIZED');
		return true;
	}

	private function remove_device($id)
	{
		Dropbox::Remove($id);
		Dobber::ReportSuccess( (is_array($id) and count($id)>1)
			? 'SUCCESS_DEVICES_NOLONGER_MEMORIZED'
			: 'SUCCESS_DEVICE_NOLONGER_MEMORIZED'
		);
		return true;
	}

	public function Add_AJAX()
	{
		if ($this->add_device($_GET['id']))
		{
			Header('X-Dropbox-Refresh: true');
		}
		$this->get_template(false, numberformat(Dropbox::GetCount()));
	}

	public function Add()
	{
		$this->add_device($_GET['id']);
		FrontController::DirectRelocate($_GET['return_to']);
	}

	public function Remove_AJAX()
	{
		if (empty($_REQUEST['id']) and isset($_REQUEST['compare']))
		{
			$_REQUEST['id'] = $_REQUEST['compare'];
		}
		if ($this->remove_device($_REQUEST['id']))
		{
			Header('X-Dropbox-Refresh: true');
		}
		$this->get_template(false, numberformat(Dropbox::GetCount()));
	}

	public function Remove()
	{
		if (empty($_REQUEST['id']) and isset($_REQUEST['compare']))
		{
			$_REQUEST['id'] = $_REQUEST['compare'];
		}
		$this->remove_device($_REQUEST['id']);
		FrontController::DirectRelocate($_GET['return_to']);
	}

	public function Get()
	{
		$this->get_template(true);
	}
}