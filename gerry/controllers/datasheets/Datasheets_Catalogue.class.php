<?php
class Datasheets_Catalogue extends Controller
{
	public function __prepare()
	{
		if (!empty($_REQUEST['view']))
		{
			Session::Set('catalogue', 'view', $_REQUEST['view']);
		}
		elseif (!Session::Get('catalogue', 'view'))
		{
			Session::Set('catalogue', 'view', 'list');
		}
	}

	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('manufacturers', Manufacturer_Name::GetAll());
	}

	public function Manufacturer()
	{
		if (empty($_GET['manufacturer_id']))
		{
			FrontController::Relocate('Index');
		}

		$this->get_template(true)
			->assign('devices', Device::GetByManufacturerId( $_GET['manufacturer_id'] ))
			->assign('manufacturer', Manufacturer_Name::Get($_GET['manufacturer_id']));
	}
}
?>