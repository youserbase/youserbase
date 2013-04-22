<?php
class YouserProfile
{
	public $id = null;
	public $first_name = null;
	public $last_name = null;
	public $street = null;
	public $street_number = null;
	public $zip = null;
	public $city = null;
	public $country = null;
	public $gender = null;
	public $day_of_birth = null;

	public function __construct($youser_id=null)
	{
		$this->id = $youser_id;

		if ($this->id!==null)
		{
			$this->load();
		}
	}

	private function load()
	{
		$result = DBManager::Get()->query("SELECT first_name, last_name, street, street_number, zip, city, country, gender, day_of_birth FROM youser_profile WHERE youser_id=?", $this->id);
		if ($result->is_empty())
		{
			return false;
		}
		$data = $result->fetchArray();

		$this->first_name = $data['first_name'];
		$this->last_name = $data['last_name'];
		$this->street = $data['street'];
		$this->street_number = $data['street_number'];
		$this->zip = $data['zip'];
		$this->city = $data['city'];
		$this->country = $data['country'];
		$this->gender = $data['gender'];
		$this->day_of_birth = $data['day_of_birth'];

		$result->release();

		return true;
	}

	public function save()
	{
		if (!$this->isValid())
		{
			throw new Exception(__CLASS__.'/'.__METHOD__.': Malformed data');
		}

		DBManager::Get()->query("INSERT INTO youser_profile (youser_id, first_name, last_name, street, street_number, zip, city, country, gender, day_of_birth) VALUES (?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE first_name=VALUES(first_name), last_name=VALUES(last_name), street=VALUES(street), street_number=VALUES(street_number), zip=VALUES(zip), city=VALUES(city), country=VALUES(country), gender=VALUES(gender), day_of_birth=VALUES(day_of_birth)",
			$this->id,
			$this->first_name,
			$this->last_name,
			$this->street,
			$this->street_number,
			$this->zip,
			$this->city,
			$this->country,
			$this->gender,
			$this->day_of_birth
		);
		return DBManager::Get()->affected_rows()>0;
	}

	public function isValid()
	{
		return !(
			empty($this->id)
		);
	}
}
?>