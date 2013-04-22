<?
class initialSheetBuilder
{
	
	public function initSheet()
	{
		$initialForm = $this->setInitialData();
		return $initialForm;
	}
	
	private function setInitialData()
	{
		$deviceinfo = new deviceinformation();
		$manufacturer = $deviceinfo->getAllManufacturers();
		$manufacturer = $this->sort($manufacturer);
		$device_types = $deviceinfo->getAllDeviceTypes();
		$device_types = $this->sort($device_types);
		return $this->buildInitialSheet($manufacturer, $device_types);
	}
	
	private function buildInitialSheet($manufacturer, $device_types)
	{
		$initialsheet = array();
		$initialsheet['manufacturer_name'] = $this->buildSelect('manufacturer_name', $manufacturer);
		$initialsheet['device_name'] = $this->buildText('device_name', 'required');
		$initialsheet['main_type'] = $this->buildSelect('main_type', $device_types);
		$initialsheet['device_types'] = $this->buildSelect('device_types', $device_types);
		return $initialsheet;
	}
	
	private function buildSelect($name, $data, $multiple='')
	{
		$select = "<select name='$name";
		if(!empty($multiple))
			$select .= "[]' multiple='$multiple'>";
		else
			$select .= "'>";
		foreach ($data as $id => $value)
		{
			$value = $value;
			$select .= '<option value="'.$value.'"><phrase id="'.$value.'"/></option>';
		}
		$select .= "</select>";
		return $select;
	}
	
	private function buildText($name, $required='')
	{
		$select ='<input type="text" name="'.$name.'" id="'.$name.'" class="required">';
		return $select;
	}
	
	private function sort($tablenames)
	{
		$lang = BabelFish::GetLanguage();
		foreach ($tablenames as $name)
		{
			$new[BabelFish::Get(strtoupper($name), $lang)] = strtoupper($name);
		}
		ksort($new);
		return $new;
	}
}

?>