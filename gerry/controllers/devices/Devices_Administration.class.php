<?php
class Devices_Administration extends Controller
{
	public function Index()
	{
		$this->get_template(false);
	}

	public function Manufacturers()
	{
		$manufacturers = investigator::getManufacturers(true);

		$template = $this->get_template(true);
		$template->assign('countries', DBManager::Get('devices')->query("SELECT country_id, country_name FROM country ORDER BY country_name ASC")->to_array('country_id', 'country_name'));
		$template->assign('manufacturers', $manufacturers);
		$template->assign('manufacturer_count', investigator::countDevicesByManufacturer(array_keys($manufacturers)));
	}

	public function Manufacturer_Edit_POST()
	{
		if (!$this->Posted('manufacturer_id', 'manufacturer_name', 'manufacturer_website', 'manufacturer_country'))
		{
			Dobber::ReportError('Ungültiger Aufruf!');
		}
		else
		{
			DBManager::Get('devices')->query("INSERT INTO manufacturer (manufacturer_id, manufacturer_name, manufacturer_website, country_id, timestamp, youser_id) VALUES (?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE manufacturer_name=VALUES(manufacturer_name), manufacturer_website=VALUES(manufacturer_website), country_id=VALUES(country_id), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",
				$_POST['manufacturer_id'],
				$_POST['manufacturer_name'],
				empty($_POST['manufacturer_website']) ? '' : $_POST['manufacturer_website'],
				empty($_POST['manufacturer_country']) ? '' : $_POST['manufacturer_country'],
				Youser::Id()
			);
			Dobber::ReportSuccess('Hersteller wurde erfolgreich bearbeitet!');

			if (isset($_FILES['manufacturer_logo']))
			{
				$name = strtolower( preg_replace('/[^[:alnum:]]/', '', str_replace('MANU_', '', $_POST['manufacturer_name'])));

				$prefix = ASSETS_IMAGE_DIR.'manufacturers/'.$name.'_logo';
				$extension = implode(array_slice(explode('/', $_FILES['manufacturer_logo']['type']), -1, 1));
				$filename = $prefix.'.original.'.$extension;

				move_uploaded_file($_FILES['manufacturer_logo']['tmp_name'], $filename);
				chmod($filename, 0777);

				Manufacturer_Image::Process($name, $filename);
				Dobber::ReportSuccess('Herstellerbilder wurden erfolgreich erneuert!');
			}
			elseif (!empty($_POST['manufacturer_logo_url']))
			{
				$name = strtolower( preg_replace('/[^[:alnum:]]/', '', str_replace('MANU_', '', $_POST['manufacturer_name'])));

				$prefix = ASSETS_IMAGE_DIR.'manufacturers/'.$name.'_logo';
				$extension = implode(array_slice(explode('.', $_POST['manufacturer_logo_url']), -1, 1));
				$filename = $prefix.'.original.'.$extension;

				$ch = curl_init($_POST['manufacturer_logo_url']);
				$fp = fopen($filename, 'w+');

				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);

				curl_exec($ch);

				curl_close($ch);
				fclose($fp);

				chmod($filename, 0666);

				Manufacturer_Image::Process($name, $filename);
				Dobber::ReportSuccess('Herstellerbilder wurden erfolgreich erneuert!');
			}

			if ($GLOBALS['VIA_AJAX'])
			{
				Lightbox::Close();
				$this->get_template(false);
				return true;
			}
		}
		FrontController::Relocate('Manufacturers');
	}

	public function Manufacturer_Edit()
	{
		$manufacturer = DBManager::Get('devices')->query("SELECT manufacturer_id, manufacturer_name, country_id, manufacturer_website FROM manufacturer WHERE manufacturer_id=?", $_REQUEST['manufacturer_id'])->fetch_item(array());

		$template = $this->get_template(true);
		$template->assign('manufacturer', $manufacturer);
	}

	public function Manufacturer_Delete()
	{
		$name = DBManager::Get('devices')->query("SELECT manufacturer_name FROM manufacturer WHERE manufacturer_id=?",
			$_GET['manufacturer_id']
		)->fetch_item();
		DBManager::Get('devices')->query("DELETE FROM manufacturer WHERE manufacturer_id=?",
			$_GET['manufacturer_id']
		);
		Dobber::ReportSuccess('Der Hersteller "%s" wurde erfolgreich gelÃ¶scht', $name);
		FrontController::Relocate('Manufacturers');
	}
}
?>
